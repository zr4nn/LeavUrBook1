<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Pastikan Model Book di-import

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data statistik dari database
        $totalBuku = Book::count();
        $sedangDibaca = Book::where('status', 'Sedang Dibaca')->count();
        $selesaiDibaca = Book::where('status', 'Selesai Dibaca')->count();
        
        // Menghitung rata-rata rating (pembulatan 1 desimal)
        $rataRating = Book::whereNotNull('rating')->avg('rating') ?? 0;
        $rataRating = number_format($rataRating, 1);

        // Kirim data ke view dashboard
        return view('dashboard', compact(
            'totalBuku', 
            'sedangDibaca', 
            'selesaiDibaca', 
            'rataRating'
        ));

        
    }
    
}