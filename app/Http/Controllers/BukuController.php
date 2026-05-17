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
    // INDEX — koleksi buku milik user, dengan pagination & filter
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $status = $request->input('status'); // filter opsional

        $query = auth()->user()
            ->userBooks()
            ->with('book')
            ->latest();

        if ($status && in_array($status, ['Sedang Dibaca', 'Selesai Dibaca', 'Daftar Tunggu'])) {
            $query->where('status', $status);
        }

        $userBooks = $query->paginate(12)->withQueryString();

        // Stats selalu dari semua buku (tidak terfilter)
        $allBooks = auth()->user()->userBooks()->get();
        $stats = [
            'total'         => $allBooks->count(),
            'sedang_dibaca' => $allBooks->where('status', 'Sedang Dibaca')->count(),
            'selesai'       => $allBooks->where('status', 'Selesai Dibaca')->count(),
            'daftar_tunggu' => $allBooks->where('status', 'Daftar Tunggu')->count(),
        ];

        return view('buku.index', compact('userBooks', 'stats', 'status'));
    }

    // -------------------------------------------------------------------------
    // SEARCH
    // -------------------------------------------------------------------------
    public function search(Request $request)
    {
        $query   = $request->input('q', '');
        $results = [];

        if ($query) {
            $results = $this->googleBooks->search($query, 20);

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
    // DETAIL
    // -------------------------------------------------------------------------
    public function detail(string $googleBooksId)
    {
        $book = Book::where('google_books_id', $googleBooksId)->first();

        if (!$book) {
            $data = $this->googleBooks->find($googleBooksId);
            if (!$data) abort(404, 'Buku tidak ditemukan.');
            $book = Book::create($data);
        }

        $userBook = null;
        if (auth()->check()) {
            $userBook = UserBook::where('user_id', auth()->id())
                                ->where('book_id', $book->id)
                                ->first();
        }

        $reviews = UserBook::where('book_id', $book->id)
                           ->whereNotNull('ulasan')
                           ->where('user_id', '!=', auth()->id() ?? 0)
                           ->with('user')
                           ->latest()
                           ->get();

        $stats = [
            'total_koleksi'  => UserBook::where('book_id', $book->id)->count(),
            'selesai_dibaca' => UserBook::where('book_id', $book->id)->where('status', 'Selesai Dibaca')->count(),
            'rating_rata'    => UserBook::where('book_id', $book->id)->whereNotNull('rating')->avg('rating'),
        ];

        return view('buku.detail', compact('book', 'userBook', 'reviews', 'stats'));
    }

    // -------------------------------------------------------------------------
    // STORE
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

        if ($validated['status'] !== 'Selesai Dibaca') {
            $validated['rating'] = null;
        }

        $book = Book::firstOrCreate(
            ['google_books_id' => $validated['google_books_id']],
            $this->googleBooks->find($validated['google_books_id']) ?? []
        );

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
    // UPDATE
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
    // TOGGLE FAVORITE
    // -------------------------------------------------------------------------
    public function toggleFavorite(string $googleBooksId)
    {
        $book = Book::where('google_books_id', $googleBooksId)->firstOrFail();

        $userBook = UserBook::where('user_id', auth()->id())
                            ->where('book_id', $book->id)
                            ->firstOrFail();

        $userBook->update(['is_favorite' => !$userBook->is_favorite]);

        $message = $userBook->is_favorite
            ? 'Buku ditambahkan ke favorit!'
            : 'Buku dihapus dari favorit.';

        if (request()->expectsJson()) {
            return response()->json([
                'is_favorite' => $userBook->is_favorite,
                'message'     => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    // -------------------------------------------------------------------------
    // DESTROY
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