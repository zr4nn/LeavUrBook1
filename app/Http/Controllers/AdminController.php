<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // -------------------------------------------------------------------------
    // DASHBOARD
    // -------------------------------------------------------------------------
    public function dashboard()
    {
        $stats = [
            'total_buku'     => Book::count(),
            'total_user'     => User::where('role', 'user')->count(),
            'sedang_dibaca'  => UserBook::where('status', 'Sedang Dibaca')->count(),
            'selesai_dibaca' => UserBook::where('status', 'Selesai Dibaca')->count(),
            'daftar_tunggu'  => UserBook::where('status', 'Daftar Tunggu')->count(),
        ];

        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentBooks = Book::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBooks'));
    }

    // -------------------------------------------------------------------------
    // DAFTAR USER
    // -------------------------------------------------------------------------
    public function users()
    {
        $users = User::where('role', 'user')
                     ->withCount('userBooks')
                     ->latest()
                     ->get();

        return view('admin.users', compact('users'));
    }

    // -------------------------------------------------------------------------
    // HAPUS USER
    // -------------------------------------------------------------------------
    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $user->delete(); // user_books cascade delete otomatis

        return back()->with('success', 'User berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // UBAH ROLE USER
    // -------------------------------------------------------------------------
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:user,admin']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role user berhasil diubah.');
    }

    // -------------------------------------------------------------------------
    // SEMUA BUKU
    // -------------------------------------------------------------------------
    public function books()
    {
        $books = Book::withCount('userBooks')->latest()->get();
        return view('admin.books', compact('books'));
    }
}