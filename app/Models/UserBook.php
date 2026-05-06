<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    protected $table = 'user_books';

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'rating',
        'ulasan',
        'halaman_saat_ini',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'rating'          => 'integer',
        'halaman_saat_ini'=> 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}