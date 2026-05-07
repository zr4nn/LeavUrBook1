@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="min-height: calc(100vh - 60px); display: flex; align-items: center; padding: 0.5rem 0 6rem 0;"><div class="w-100">
<style>
    .auth-card {
        max-width: 420px;
        margin: 0 auto;
    }

    .form-control {
        background-color: var(--warm-white, #fafafa);
        border: 1px solid var(--border, #dee2e6);
        color: var(--ink);
    }

    .form-control:focus {
        border-color: var(--amber, #C4894A);
        box-shadow: 0 0 0 0.25rem rgba(196, 137, 74, 0.1);
        background-color: white;
    }

    .btn-amber {
        background-color: var(--amber, #C4894A);
        color: white;
        border: none;
    }

    .btn-amber:hover {
        background-color: #b07840;
        color: white;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #adb5bd;
        font-size: 0.85rem;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border, #dee2e6);
    }
</style>

<div class="auth-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Login to Continue</h3>
        <p class="text-muted small mt-1">
            Scared to lose your progress? <i><u>LeavUrBook</u></i> here.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">


        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted text-uppercase">Email</label>
                <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="nama@email.com" autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-muted text-uppercase">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordInput"
                        class="form-control rounded-3 @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 text-muted"
                        onclick="togglePassword()" style="background:none; border:none; z-index:5;">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-amber w-100 rounded-pill py-2 fw-bold">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <div class="auth-divider my-4">atau</div>

        <p class="text-center text-muted small mb-0">
            Belum punya akun?
            <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--amber, #C4894A);">Daftar sekarang</a>
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</div>
</div>
@endsection