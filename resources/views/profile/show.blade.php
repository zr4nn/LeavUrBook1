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

    /* Tab buttons */
    .tab-btn {
        border: 1.5px solid var(--border, #dee2e6);
        background: white;
        border-radius: 50px;
        padding: 0.45rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .tab-btn:hover {
        border-color: var(--amber, #C4894A);
        color: var(--amber, #C4894A);
        background: #fffaf5;
    }
    .tab-btn.active {
        background-color: var(--amber, #C4894A);
        border-color: var(--amber, #C4894A);
        color: white;
    }
    .tab-btn-danger { border-color: #f5c6cb; color: #dc3545; }
    .tab-btn-danger:hover { background: #fff5f5; border-color: #dc3545; color: #dc3545; }
    .tab-btn-danger.active-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* Avatar */
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

    /* Stat pill */
    .stat-pill {
        background: var(--warm-white, #f8f8f8);
        border: 1px solid var(--border, #eee);
        border-radius: 50px;
        padding: 0.4rem 1.25rem;
        font-size: 0.8rem;
        text-align: center;
        min-width: 90px;
    }

    /* Danger zone */
    .danger-zone {
        border: 1px solid #f8d7da;
        border-radius: 12px;
        background: #fff5f5;
    }

    /* Password reveal */
    .password-field-wrapper {
        position: relative;
    }
    .password-field-wrapper .toggle-pass {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        padding: 0;
        font-size: 0.9rem;
        z-index: 5;
    }
    .password-field-wrapper .toggle-pass:hover { color: var(--amber, #C4894A); }

    /* Password display box */
    .password-display-box {
        background: #f8f9fa;
        border: 1px solid var(--border, #dee2e6);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
    }
    .password-stars {
        font-size: 1.5rem;
        letter-spacing: 4px;
        color: #adb5bd;
        font-family: monospace;
    }
    .password-reveal-text {
        font-size: 1.1rem;
        font-family: monospace;
        letter-spacing: 2px;
        color: var(--ink);
        word-break: break-all;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- HEADER PROFIL --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex align-items-start gap-4 flex-wrap">

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

                {{-- Hapus foto — dipisah agar tidak nabrak --}}
                @if($user->avatar)
                <form action="{{ route('profile.avatar.delete') }}" method="POST"
                      onsubmit="return confirm('Hapus foto profil?')" class="flex-shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm rounded-pill px-3"
                            style="border: 1.5px solid #dc3545; color: #dc3545; background: white;">
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
                    <div class="fw-bold" style="color: var(--amber, #C4894A);">{{ $stats['sedang_dibaca'] }}</div>
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
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
                <button class="tab-btn {{ !in_array(session('tab'), ['password','lihat-password','hapus']) ? 'active' : '' }}"
                        onclick="switchTab('edit')" id="btnEdit">
                    <i class="fas fa-user-edit me-1"></i> Edit Profil
                </button>
                <button class="tab-btn {{ session('tab') == 'password' ? 'active' : '' }}"
                        onclick="switchTab('password')" id="btnPassword">
                    <i class="fas fa-lock me-1"></i> Ganti Password
                </button>
                <button class="tab-btn {{ session('tab') == 'lihat-password' ? 'active' : '' }}"
                        onclick="switchTab('lihat-password')" id="btnLihatPassword">
                    <i class="fas fa-eye me-1"></i> Lihat Password
                </button>
                <button class="tab-btn tab-btn-danger {{ session('tab') == 'hapus' ? 'active-danger' : '' }}"
                        onclick="switchTab('hapus')" id="btnHapus">
                    <i class="fas fa-user-times me-1"></i> Hapus Akun
                </button>
            </div>
        </div>

        {{-- TAB EDIT PROFIL --}}
        <div id="tab-edit" class="tab-pane {{ in_array(session('tab'), ['password','lihat-password','hapus']) ? 'd-none' : '' }}">
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
                            <hr class="opacity-10 mb-3">
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
                            <div class="password-field-wrapper">
                                <input type="password" name="current_password" id="currentPass"
                                    class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                    placeholder="Password saat ini">
                                <button type="button" class="toggle-pass" onclick="togglePass('currentPass', 'eyeCurrent')">
                                    <i class="fas fa-eye" id="eyeCurrent"></i>
                                </button>
                            </div>
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                            <div class="password-field-wrapper">
                                <input type="password" name="password" id="newPass"
                                    class="form-control rounded-3 @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 karakter">
                                <button type="button" class="toggle-pass" onclick="togglePass('newPass', 'eyeNew')">
                                    <i class="fas fa-eye" id="eyeNew"></i>
                                </button>
                            </div>
                            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Password Baru</label>
                            <div class="password-field-wrapper">
                                <input type="password" name="password_confirmation" id="confirmPass"
                                    class="form-control rounded-3" placeholder="Ulangi password baru">
                                <button type="button" class="toggle-pass" onclick="togglePass('confirmPass', 'eyeConfirm')">
                                    <i class="fas fa-eye" id="eyeConfirm"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <hr class="opacity-10 mb-3">
                            <button type="submit" class="btn rounded-pill px-5"
                                    style="background-color:var(--amber,#C4894A); color:white; border:none;">
                                <i class="fas fa-key me-2"></i> Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB LIHAT PASSWORD --}}
        <div id="tab-lihat-password" class="tab-pane {{ session('tab') == 'lihat-password' ? '' : 'd-none' }}">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-1" style="color:var(--ink);">
                    <i class="fas fa-eye me-2" style="color:var(--amber,#C4894A);"></i>Lihat Password
                </h5>
                <p class="text-muted small mb-4">Masukkan password lama untuk memverifikasi identitasmu.</p>

                {{-- Form verifikasi --}}
                <div id="verifySection">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted text-uppercase">Verifikasi dengan Password Lama</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="verifyInput"
                                    class="form-control rounded-3"
                                    placeholder="Masukkan passwordmu">
                                <button type="button" class="toggle-pass" onclick="togglePass('verifyInput', 'eyeVerify')">
                                    <i class="fas fa-eye" id="eyeVerify"></i>
                                </button>
                            </div>
                            <div class="text-danger small mt-1 d-none" id="verifyError">Password salah. Coba lagi.</div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn rounded-pill px-4 w-100"
                                    style="background-color:var(--amber,#C4894A); color:white; border:none;"
                                    onclick="verifyPassword()">
                                <i class="fas fa-unlock me-2"></i> Verifikasi
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Hasil password (tersembunyi awalnya) --}}
                <div id="passwordResult" class="d-none mt-4">
                    <div class="password-display-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold small text-muted text-uppercase">Password Kamu</span>
                            <button type="button" class="btn btn-sm rounded-pill px-3"
                                    style="border: 1.5px solid var(--amber,#C4894A); color:var(--amber,#C4894A); background:white; font-size:0.75rem;"
                                    onclick="togglePasswordDisplay()">
                                <i class="fas fa-eye me-1" id="eyeDisplay"></i>
                                <span id="eyeDisplayText">Tampilkan</span>
                            </button>
                        </div>
                        <div class="password-stars" id="passStars">••••••••••••</div>
                        <div class="password-reveal-text d-none" id="passText"></div>
                    </div>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-muted"></i>
                        <small class="text-muted">Jangan bagikan passwordmu kepada siapapun.</small>
                    </div>
                </div>
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
                        <div class="password-field-wrapper">
                            <input type="password" name="confirm_password" id="deletePass"
                                class="form-control rounded-3 @error('confirm_password') is-invalid @enderror"
                                placeholder="Masukkan passwordmu untuk konfirmasi">
                            <button type="button" class="toggle-pass" onclick="togglePass('deletePass', 'eyeDelete')">
                                <i class="fas fa-eye" id="eyeDelete"></i>
                            </button>
                        </div>
                        @error('confirm_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
    // Switch tab
    function switchTab(tab) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('d-none'));
        document.getElementById('tab-' + tab).classList.remove('d-none');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'active-danger');
        });

        const map = {
            'edit': 'btnEdit',
            'password': 'btnPassword',
            'lihat-password': 'btnLihatPassword',
            'hapus': 'btnHapus'
        };
        const btn = document.getElementById(map[tab]);
        if (btn) {
            if (tab === 'hapus') btn.classList.add('active-danger');
            else btn.classList.add('active');
        }
    }

    // Set active tab on load
    @if(session('tab') == 'hapus')
        document.getElementById('btnHapus').classList.add('active-danger');
    @elseif(session('tab') == 'password')
        document.getElementById('btnPassword').classList.add('active');
    @elseif(session('tab') == 'lihat-password')
        document.getElementById('btnLihatPassword').classList.add('active');
    @else
        document.getElementById('btnEdit').classList.add('active');
    @endif

    // Toggle show/hide password input
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Verifikasi password via AJAX
    function verifyPassword() {
        const password = document.getElementById('verifyInput').value;
        const errEl    = document.getElementById('verifyError');
        const resultEl = document.getElementById('passwordResult');

        if (!password) {
            errEl.textContent = 'Masukkan passwordmu terlebih dahulu.';
            errEl.classList.remove('d-none');
            return;
        }

        fetch('{{ route("profile.verify-password") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                errEl.classList.add('d-none');
                document.getElementById('passText').textContent = data.password;
                resultEl.classList.remove('d-none');
            } else {
                errEl.textContent = 'Password salah. Coba lagi.';
                errEl.classList.remove('d-none');
                resultEl.classList.add('d-none');
            }
        })
        .catch(() => {
            errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
            errEl.classList.remove('d-none');
        });
    }

    // Toggle tampil/sembunyikan password hasil
    function togglePasswordDisplay() {
        const stars  = document.getElementById('passStars');
        const text   = document.getElementById('passText');
        const icon   = document.getElementById('eyeDisplay');
        const label  = document.getElementById('eyeDisplayText');

        if (text.classList.contains('d-none')) {
            stars.classList.add('d-none');
            text.classList.remove('d-none');
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            label.textContent = 'Sembunyikan';
        } else {
            stars.classList.remove('d-none');
            text.classList.add('d-none');
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            label.textContent = 'Tampilkan';
        }
    }

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

    // Enter key untuk verifikasi password
    document.getElementById('verifyInput')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); verifyPassword(); }
    });
</script>
@endsection