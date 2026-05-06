<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // -------------------------------------------------------------------------
    // SHOW LOGIN FORM
    // -------------------------------------------------------------------------
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    // -------------------------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------------------------
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    // -------------------------------------------------------------------------
    // SHOW REGISTER FORM
    // -------------------------------------------------------------------------
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    // -------------------------------------------------------------------------
    // REGISTER
    // -------------------------------------------------------------------------
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip (-), dan underscore (_).',
            'username.unique'     => 'Username sudah digunakan.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.min'        => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user', // default selalu user
        ]);

        Auth::login($user);

        return redirect()->route('buku.index')
                         ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    // -------------------------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------------------------
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Kamu berhasil logout.');
    }

    // -------------------------------------------------------------------------
    // HELPER: redirect sesuai role
    // -------------------------------------------------------------------------
    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('buku.index');
    }
}