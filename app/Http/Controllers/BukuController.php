<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBook;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function __construct(private GoogleBooksService $googleBooks) {}

    // -------------------------------------------------------------------------
    // INDEX — koleksi buku milik user yang login
    // -------------------------------------------------------------------------
    public function index()
    {
        $userBooks = auth()->user()
            ->userBooks()
            ->with('book')
            ->latest()
            ->get();

        $stats = [
            'total'         => $userBooks->count(),
            'sedang_dibaca' => $userBooks->where('status', 'Sedang Dibaca')->count(),
            'selesai'       => $userBooks->where('status', 'Selesai Dibaca')->count(),
            'daftar_tunggu' => $userBooks->where('status', 'Daftar Tunggu')->count(),
        ];

        return view('buku.index', compact('userBooks', 'stats'));
    }

    // -------------------------------------------------------------------------
    // SEARCH — halaman pencarian buku via Google Books API
    // -------------------------------------------------------------------------
    public function search(Request $request)
    {
        $query   = $request->input('q', '');
        $results = [];

        if ($query) {
            $results = $this->googleBooks->search($query, 20);

            // Tandai buku yang sudah ada di koleksi user
            $myGoogleIds = auth()->user()
                ->books()
                ->pluck('google_books_id')
                ->toArray();

            $results = array_map(function ($book) use ($myGoogleIds) {
                $book['in_collection'] = in_array($book['google_books_id'], $myGoogleIds);
                return $book;
            }, $results);
        }

        return view('buku.search', compact('query', 'results'));
    }

    // -------------------------------------------------------------------------
    // DETAIL — halaman detail buku (Letterboxd style)
    // -------------------------------------------------------------------------
    public function detail(string $googleBooksId)
    {
        // Cek apakah sudah ada di DB kita
        $book = Book::where('google_books_id', $googleBooksId)->first();

        // Kalau belum, ambil dari API dan simpan
        if (!$book) {
            $data = $this->googleBooks->find($googleBooksId);
            if (!$data) abort(404, 'Buku tidak ditemukan.');
            $book = Book::create($data);
        }

        // Data user untuk buku ini (jika sudah di koleksi)
        $userBook = null;
        if (auth()->check()) {
            $userBook = UserBook::where('user_id', auth()->id())
                                ->where('book_id', $book->id)
                                ->first();
        }

        // Ulasan publik dari semua user (kecuali milik sendiri)
        $reviews = UserBook::where('book_id', $book->id)
                           ->whereNotNull('ulasan')
                           ->where('user_id', '!=', auth()->id() ?? 0)
                           ->with('user')
                           ->latest()
                           ->get();

        // Statistik buku
        $stats = [
            'total_koleksi'  => UserBook::where('book_id', $book->id)->count(),
            'selesai_dibaca' => UserBook::where('book_id', $book->id)->where('status', 'Selesai Dibaca')->count(),
            'rating_rata'    => UserBook::where('book_id', $book->id)->whereNotNull('rating')->avg('rating'),
        ];

        return view('buku.detail', compact('book', 'userBook', 'reviews', 'stats'));
    }

    // -------------------------------------------------------------------------
    // STORE — tambah/update buku ke koleksi user
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'google_books_id'  => 'required|string',
            'status'           => 'required|in:Daftar Tunggu,Sedang Dibaca,Selesai Dibaca',
            'rating'           => 'nullable|integer|min:1|max:5',
            'ulasan'           => 'nullable|string|max:2000',
            'halaman_saat_ini' => 'nullable|integer|min:0',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        // Rating hanya boleh jika status Selesai Dibaca
        if ($validated['status'] !== 'Selesai Dibaca') {
            $validated['rating'] = null;
        }

        // Pastikan buku ada di DB kita
        $book = Book::firstOrCreate(
            ['google_books_id' => $validated['google_books_id']],
            $this->googleBooks->find($validated['google_books_id']) ?? []
        );

        // Upsert ke user_books
        UserBook::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            [
                'status'           => $validated['status'],
                'rating'           => $validated['rating'] ?? null,
                'ulasan'           => $validated['ulasan'] ?? null,
                'halaman_saat_ini' => $validated['halaman_saat_ini'] ?? 0,
                'tanggal_mulai'    => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai'  => $validated['tanggal_selesai'] ?? null,
            ]
        );

        return redirect()->route('buku.detail', $validated['google_books_id'])
                         ->with('success', 'Buku berhasil ditambahkan ke koleksimu!');
    }

    // -------------------------------------------------------------------------
    // UPDATE — update data koleksi user untuk buku tertentu
    // -------------------------------------------------------------------------
    public function update(Request $request, string $googleBooksId)
    {
        $validated = $request->validate([
            'status'           => 'required|in:Daftar Tunggu,Sedang Dibaca,Selesai Dibaca',
            'rating'           => 'nullable|integer|min:1|max:5',
            'ulasan'           => 'nullable|string|max:2000',
            'halaman_saat_ini' => 'nullable|integer|min:0',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        // Rating hanya boleh jika status Selesai Dibaca
        if ($validated['status'] !== 'Selesai Dibaca') {
            $validated['rating'] = null;
        }

        $book = Book::where('google_books_id', $googleBooksId)->firstOrFail();

        UserBook::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            $validated
        );

        return redirect()->route('buku.detail', $googleBooksId)
                         ->with('success', 'Koleksimu berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // DESTROY — hapus buku dari koleksi user
    // -------------------------------------------------------------------------
    public function destroy(string $googleBooksId)
    {
        $book = Book::where('google_books_id', $googleBooksId)->firstOrFail();

        UserBook::where('user_id', auth()->id())
                ->where('book_id', $book->id)
                ->delete();

        return redirect()->route('buku.index')
                         ->with('success', 'Buku dihapus dari koleksimu.');
    }
}