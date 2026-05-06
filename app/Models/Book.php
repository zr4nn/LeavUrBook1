<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'google_books_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'deskripsi',
        'cover_url',
        'total_halaman',
        'isbn',
        'genre',
        'bahasa',
    ];

    // Relasi many-to-many ke User via user_books
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_books')
                    ->withPivot(['status', 'rating', 'ulasan', 'halaman_saat_ini', 'tanggal_mulai', 'tanggal_selesai'])
                    ->withTimestamps();
    }

    // Semua entri user_books untuk buku ini
    public function userBooks()
    {
        return $this->hasMany(UserBook::class);
    }

    // Ulasan publik (yang sudah selesai dibaca dan punya ulasan)
    public function reviews()
    {
        return $this->hasMany(UserBook::class)
                    ->whereNotNull('ulasan')
                    ->with('user');
    }

    // Rating rata-rata
    public function getRatingRataAttribute(): ?float
    {
        $avg = $this->userBooks()->whereNotNull('rating')->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    // Jumlah yang sudah baca
    public function getTotalPembacaAttribute(): int
    {
        return $this->userBooks()->where('status', 'Selesai Dibaca')->count();
    }
}