@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<style>
    .auth-card { max-width: 480px; margin: 0 auto; }
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
    .btn-amber { background-color: var(--amber, #C4894A); color: white; border: none; }
    .btn-amber:hover { background-color: #b07840; color: white; }
    .auth-divider {
        display: flex; align-items: center; gap: 1rem;
        color: #adb5bd; font-size: 0.85rem;
    }
    .auth-divider::before, .auth-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border, #dee2e6);
    }
</style>

<div class="auth-card">
    <div class="text-center mb-4">
        <i class="fas fa-book-open fs-1 mb-2" style="color: var(--amber, #C4894A);"></i>
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Buat Akun Baru</h3>
        <p class="text-muted small mt-1">Bergabung dan mulai catat koleksimu 📚</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Nama lengkapmu" autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Username</label>
                    <div class="input-group">
                        <span class="input-group-text rounded-start-3 bg-light border-end-0 text-muted">@</span>
                        <input type="text" name="username" class="form-control rounded-end-3 @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" placeholder="username_kamu">
                    </div>
                    @error('username')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Email</label>
                    <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="nama@email.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Nomor Telepon <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="passwordInput"
                            class="form-control rounded-3 @error('password') is-invalid @enderror"
                            placeholder="Min. 8 karakter">
                        <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 text-muted"
                                onclick="togglePass('passwordInput','eye1')" style="background:none;border:none;z-index:5;">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Password</label>
                    <div class="position-relative">
                        <input type="password" name="password_confirmation" id="passwordConfirm"
                            class="form-control rounded-3"
                            placeholder="Ulangi password">
                        <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 text-muted"
                                onclick="togglePass('passwordConfirm','eye2')" style="background:none;border:none;z-index:5;">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-amber w-100 rounded-pill py-2 fw-bold">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>
                </div>
            </div>
        </form>

        <div class="auth-divider my-4">atau</div>

        <p class="text-center text-muted small mb-0">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--amber, #C4894A);">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection