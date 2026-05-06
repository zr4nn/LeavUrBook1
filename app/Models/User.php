<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'phone',
        'avatar', 'bio', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'user_books')
                    ->withPivot(['status', 'rating', 'ulasan', 'halaman_saat_ini', 'tanggal_mulai', 'tanggal_selesai'])
                    ->withTimestamps();
    }

    public function userBooks()
    {
        return $this->hasMany(UserBook::class);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isUser(): bool  { return $this->role === 'user'; }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) return Storage::url($this->avatar);
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C4894A&color=fff&size=256';
    }
} 