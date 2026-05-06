<?php

// ============================================================
// PENTING: Jalankan migration ini SETELAH menghapus/mereset
// tabel books lama. Atau buat fresh migration jika perlu.
//
// Jika tabel books sudah ada, DROP dulu via tinker:
//   Schema::drop('books');
// Lalu jalankan: php artisan migrate
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel lama jika ada
        Schema::dropIfExists('user_books');
        Schema::dropIfExists('books');

        // Tabel books — data master dari Google Books API (shared antar user)
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('google_books_id')->unique();   // ID dari Google Books API
            $table->string('judul');
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cover_url')->nullable();       // URL cover dari Google Books
            $table->integer('total_halaman')->nullable();
            $table->string('isbn')->nullable();
            $table->string('genre')->nullable();           // categories dari API
            $table->string('bahasa', 10)->nullable();
            $table->timestamps();
        });

        // Tabel user_books — data personal per user per buku
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['Daftar Tunggu', 'Sedang Dibaca', 'Selesai Dibaca'])->default('Daftar Tunggu');
            $table->unsignedTinyInteger('rating')->nullable();    // 1-5
            $table->text('ulasan')->nullable();
            $table->unsignedInteger('halaman_saat_ini')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'book_id']); // satu user hanya bisa punya 1 entri per buku
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_books');
        Schema::dropIfExists('books');
    }
};