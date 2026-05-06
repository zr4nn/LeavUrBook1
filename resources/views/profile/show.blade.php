@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<style>
    .form-control, .form-select {
        background-color: var(--warm-white, #fafafa);
        border: 1px solid var(--border, #dee2e6);
        color: var(--ink);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--amber, #C4894A);
        box-shadow: 0 0 0 0.25rem rgba(196,137,74,0.1);
        background-color: white;
    }
    .nav-pills .nav-link {
        color: var(--ink, #333);
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .nav-pills .nav-link.active {
        background-color: var(--amber, #C4894A);
        color: white;
    }
    .avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
    }
    .avatar-wrapper img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    .avatar-edit-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--amber, #C4894A);
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        transition: transform 0.2s;
    }
    .avatar-edit-btn:hover { transform: scale(1.1); }
    .stat-pill {
        background: var(--warm-white, #f8f8f8);
        border: 1px solid var(--border, #eee);
        border-radius: 50px;
        padding: 0.4rem 1.25rem;
        font-size: 0.8rem;
        text-align: center;
        min-width: 90px;
    }
    .danger-zone {
        border: 1px solid #f8d7da;
        border-radius: 12px;
        background: #fff5f5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-9">

        @if(session('success'))
            <div class="alert alert-success rounded-3 small py-2 mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- HEADER PROFIL --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">

                {{-- Avatar --}}
                <div class="avatar-wrapper flex-shrink-0">
                    <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                    <div class="avatar-edit-btn" onclick="document.getElementById('avatarInput').click()" title="Ganti foto">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>

                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf @method('PUT')
                    <input type="file" id="avatarInput" name="avatar" class="d-none"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewAvatar(this)">
                </form>

                {{-- Info --}}
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                        @if($user->isAdmin())
                            <span class="badge rounded-pill px-3" style="background-color: var(--amber, #C4894A); font-size:0.7rem;">
                                <i class="fas fa-shield-alt me-1"></i> Admin
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small mb-2 d-flex flex-wrap gap-3">
                        @if($user->username)<span><i class="fas fa-at me-1"></i>{{ $user->username }}</span>@endif
                        @if($user->email)<span><i class="fas fa-envelope me-1"></i>{{ $user->email }}</span>@endif
                        @if($user->phone)<span><i class="fas fa-phone me-1"></i>{{ $user->phone }}</span>@endif
                    </div>
                    @if($user->bio)
                        <p class="text-muted small mb-2" style="font-style:italic;">"{{ $user->bio }}"</p>
                    @endif
                    <div class="text-muted" style="font-size:0.75rem;">
                        <i class="fas fa-calendar-alt me-1"></i> Bergabung {{ $user->created_at->format('d F Y') }}
                    </div>
                </div>

                @if($user->avatar)
                <form action="{{ route('profile.avatar.delete') }}" method="POST"
                      onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fas fa-trash me-1"></i> Hapus Foto
                    </button>
                </form>
                @endif
            </div>

            {{-- Statistik --}}
            <hr class="my-3 opacity-25">
            <div class="d-flex gap-3 flex-wrap">
                <div class="stat-pill">
                    <div class="fw-bold" style="color:var(--ink);">{{ $stats['total'] }}</div>
                    <div class="text-muted" style="font-size:0.7rem;">Total Buku</div>
                </div>
                <div class="stat-pill">
                    <div class="fw-bold text-warning">{{ $stats['sedang_dibaca'] }}</div>
                    <div class="text-muted" style="font-size:0.7rem;">Sedang Dibaca</div>
                </div>
                <div class="stat-pill">
                    <div class="fw-bold text-success">{{ $stats['selesai'] }}</div>
                    <div class="text-muted" style="font-size:0.7rem;">Selesai Dibaca</div>
                </div>
                <div class="stat-pill">
                    <div class="fw-bold text-secondary">{{ $stats['daftar_tunggu'] }}</div>
                    <div class="text-muted" style="font-size:0.7rem;">Daftar Tunggu</div>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <ul class="nav nav-pills mb-4 gap-2" id="profileTab">
            <li class="nav-item">
                <button class="nav-link {{ !in_array(session('tab'), ['password','hapus']) ? 'active' : '' }}"
                        onclick="switchTab('edit')">
                    <i class="fas fa-user-edit me-1"></i> Edit Profil
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ session('tab') == 'password' ? 'active' : '' }}"
                        onclick="switchTab('password')">
                    <i class="fas fa-lock me-1"></i> Ganti Password
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ session('tab') == 'hapus' ? 'active' : '' }}"
                        id="btnHapus" onclick="switchTab('hapus')">
                    <i class="fas fa-user-times me-1"></i> Hapus Akun
                </button>
            </li>
        </ul>

        {{-- TAB EDIT PROFIL --}}
        <div id="tab-edit" class="tab-pane {{ in_array(session('tab'), ['password','hapus']) ? 'd-none' : '' }}">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4" style="color:var(--ink);">Edit Profil</h5>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Username</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-3 bg-light border-end-0 text-muted">@</span>
                                <input type="text" name="username" class="form-control rounded-end-3 @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}">
                            </div>
                            @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email</label>
                            <input type="email" class="form-control rounded-3 bg-light" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak bisa diubah.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control rounded-3"
                                value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Bio</label>
                            <textarea name="bio" class="form-control rounded-3" rows="3"
                                placeholder="Ceritakan sedikit tentang dirimu..." maxlength="500"
                                id="bioTextarea">{{ old('bio', $user->bio) }}</textarea>
                            <div class="text-end">
                                <small class="text-muted" id="bioCount">{{ strlen($user->bio ?? '') }}/500</small>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <hr class="opacity-10">
                            <button type="submit" class="btn rounded-pill px-5"
                                    style="background-color:var(--amber,#C4894A); color:white; border:none;">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB GANTI PASSWORD --}}
        <div id="tab-password" class="tab-pane {{ session('tab') == 'password' ? '' : 'd-none' }}">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4" style="color:var(--ink);">Ganti Password</h5>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Password Lama</label>
                            <input type="password" name="current_password"
                                class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                placeholder="Password saat ini">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Min. 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="form-control rounded-3" placeholder="Ulangi password baru">
                        </div>
                        <div class="col-12 text-end mt-2">
                            <hr class="opacity-10">
                            <button type="submit" class="btn rounded-pill px-5"
                                    style="background-color:var(--amber,#C4894A); color:white; border:none;">
                                <i class="fas fa-key me-2"></i> Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB HAPUS AKUN --}}
        <div id="tab-hapus" class="tab-pane {{ session('tab') == 'hapus' ? '' : 'd-none' }}">
            <div class="card border-0 shadow-sm rounded-4 p-4 danger-zone">
                <h5 class="fw-bold mb-2 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Hapus Akun
                </h5>
                <p class="text-muted small mb-4">
                    Tindakan ini <strong>tidak bisa dibatalkan</strong>. Semua data koleksi bukumu akan ikut terhapus secara permanen.
                </p>
                <form action="{{ route('profile.destroy') }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan!')">
                    @csrf @method('DELETE')
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi dengan Password</label>
                        <input type="password" name="confirm_password"
                            class="form-control rounded-3 @error('confirm_password') is-invalid @enderror"
                            placeholder="Masukkan passwordmu untuk konfirmasi">
                        @error('confirm_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-danger rounded-pill px-5">
                        <i class="fas fa-trash me-2"></i> Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('d-none'));
        document.getElementById('tab-' + tab).classList.remove('d-none');
        document.querySelectorAll('#profileTab .nav-link').forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = '';
            btn.style.color = '';
        });
        const btn = document.querySelector(`#profileTab button[onclick="switchTab('${tab}')"]`);
        if (btn) {
            btn.classList.add('active');
            if (tab === 'hapus') {
                btn.style.backgroundColor = '#dc3545';
                btn.style.color = 'white';
            }
        }
    }

    // Set tab hapus style on load jika aktif
    @if(session('tab') == 'hapus')
        document.getElementById('btnHapus').style.backgroundColor = '#dc3545';
        document.getElementById('btnHapus').style.color = 'white';
    @endif

    // Preview avatar
    function previewAvatar(input) {
        if (!input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
        document.getElementById('avatarForm').submit();
    }

    // Counter bio
    const bio = document.getElementById('bioTextarea');
    if (bio) {
        bio.addEventListener('input', function() {
            document.getElementById('bioCount').textContent = this.value.length + '/500';
        });
    }
</script>
@endsection