<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    private string $baseUrl = 'https://www.googleapis.com/books/v1';
    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');
    }

    // -------------------------------------------------------------------------
    // Cari buku berdasarkan query
    // -------------------------------------------------------------------------
    public function search(string $query, int $maxResults = 20, int $startIndex = 0): array
    {
        $params = [
            'q'          => $query,
            'maxResults' => $maxResults,
            'startIndex' => $startIndex,
            'langRestrict' => 'id', // prioritas buku Indonesia, bisa dihapus
        ];

        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }

        $response = Http::timeout(10)->get("{$this->baseUrl}/volumes", $params);

        if ($response->failed()) return [];

        $data  = $response->json();
        $items = $data['items'] ?? [];

        return array_map(fn($item) => $this->formatVolume($item), $items);
    }

    // -------------------------------------------------------------------------
    // Ambil detail buku berdasarkan Google Books ID
    // -------------------------------------------------------------------------
    public function find(string $googleBooksId): ?array
    {
        $params = [];
        if ($this->apiKey) $params['key'] = $this->apiKey;

        $response = Http::timeout(10)->get("{$this->baseUrl}/volumes/{$googleBooksId}", $params);

        if ($response->failed()) return null;

        return $this->formatVolume($response->json());
    }

    // -------------------------------------------------------------------------
    // Format data volume dari API ke format yang konsisten
    // -------------------------------------------------------------------------
    private function formatVolume(array $item): array
    {
        $info = $item['volumeInfo'] ?? [];

        // Cover — ambil thumbnail terbesar yang tersedia
        $covers    = $info['imageLinks'] ?? [];
        $coverUrl  = $covers['extraLarge']
                  ?? $covers['large']
                  ?? $covers['medium']
                  ?? $covers['thumbnail']
                  ?? $covers['smallThumbnail']
                  ?? null;

        // Ganti http ke https dan hapus curl parameter
        if ($coverUrl) {
            $coverUrl = str_replace('http://', 'https://', $coverUrl);
            $coverUrl = preg_replace('/&curl=\d/', '', $coverUrl);
        }

        // ISBN
        $isbn = null;
        foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                $isbn = $identifier['identifier'];
                break;
            }
        }
        if (!$isbn) {
            foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
                if ($identifier['type'] === 'ISBN_10') {
                    $isbn = $identifier['identifier'];
                    break;
                }
            }
        }

        // Tahun terbit
        $publishedDate = $info['publishedDate'] ?? null;
        $tahun = $publishedDate ? (int) substr($publishedDate, 0, 4) : null;

        return [
            'google_books_id' => $item['id'],
            'judul'           => $info['title'] ?? 'Tanpa Judul',
            'penulis'         => implode(', ', $info['authors'] ?? []) ?: null,
            'penerbit'        => $info['publisher'] ?? null,
            'tahun_terbit'    => $tahun,
            'deskripsi'       => $info['description'] ?? null,
            'cover_url'       => $coverUrl,
            'total_halaman'   => $info['pageCount'] ?? null,
            'isbn'            => $isbn,
            'genre'           => implode(', ', $info['categories'] ?? []) ?: null,
            'bahasa'          => $info['language'] ?? null,
        ];
    }
}