<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('genre')->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->string('isbn')->nullable();
            
            // Kolom Status
            $table->enum('status', ['Daftar Tunggu', 'Sedang Dibaca', 'Selesai Dibaca'])->default('Daftar Tunggu');
            
            // Kolom Progres Membaca
            $table->integer('halaman_saat_ini')->default(0); // Halaman yang sedang dibaca
            $table->integer('total_halaman')->nullable();     // Total halaman buku
            
            // Kolom Rating & Ulasan
            $table->integer('rating')->nullable();
            $table->text('ulasan')->nullable();
            
            $table->timestamps();
        });
    }

        
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};