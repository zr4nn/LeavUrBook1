<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // -------------------------------------------------------------------------
    // PROFIL PUBLIK USER LAIN
    // -------------------------------------------------------------------------
    public function showPublic(User $user)
    {
        $userBooks = UserBook::where('user_id', $user->id)
                             ->with('book')
                             ->latest()
                             ->get();

        $stats = [
            'total'         => $userBooks->count(),
            'sedang_dibaca' => $userBooks->where('status', 'Sedang Dibaca')->count(),
            'selesai'       => $userBooks->where('status', 'Selesai Dibaca')->count(),
            'daftar_tunggu' => $userBooks->where('status', 'Daftar Tunggu')->count(),
        ];

        return view('profile.public', compact('user', 'userBooks', 'stats'));
    }

    // -------------------------------------------------------------------------
    // SHOW PROFIL SENDIRI
    // -------------------------------------------------------------------------
    public function show()
    {
        $user  = auth()->user();
        $books = $user->userBooks()->get();

        $stats = [
            'total'         => $books->count(),
            'sedang_dibaca' => $books->where('status', 'Sedang Dibaca')->count(),
            'selesai'       => $books->where('status', 'Selesai Dibaca')->count(),
            'daftar_tunggu' => $books->where('status', 'Daftar Tunggu')->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    // -------------------------------------------------------------------------
    // UPDATE PROFIL
    // -------------------------------------------------------------------------
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'bio'      => 'nullable|string|max:500',
        ], [
            'username.unique'     => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // UPDATE AVATAR
    // -------------------------------------------------------------------------
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // HAPUS AVATAR
    // -------------------------------------------------------------------------
    public function deleteAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // GANTI PASSWORD
    // -------------------------------------------------------------------------
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->with('tab', 'password');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui!')->with('tab', 'password');
    }

    // -------------------------------------------------------------------------
    // HAPUS AKUN
    // -------------------------------------------------------------------------
    public function destroy(Request $request)
    {
        $request->validate(['confirm_password' => 'required']);

        $user = auth()->user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()
                ->withErrors(['confirm_password' => 'Password tidak sesuai.'])
                ->with('tab', 'hapus');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun berhasil dihapus.');
    }
}