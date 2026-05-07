<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" style="padding-top: 0.6rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border, #f0ece6);">
    <div class="container">
        <a class="navbar-brand fw-bold d-inline-flex align-items-center gap-2 text-decoration-none"
            style="color: var(--ink, #1C1810); font-size: 1.1rem; letter-spacing: -0.02em;"
            href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('buku.index')) : route('home') }}">
            <img src="{{ asset('images/WhatsApp_Image_2026-05-07_at_08.14.07-removebg-preview.png') }}"
                alt="LeavUrBook" width="34" height="34"
                style="display:block; width:34px; height:34px; object-fit:cover; border-radius:10px;">
            <span>LeavUrBook</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                @auth
                @if(auth()->user()->isAdmin())
                {{-- Menu Admin --}}
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                        href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-1"></i> Kelola User
                    </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('admin/books*') ? 'active' : '' }}"
                        href="{{ route('admin.books') }}">
                        <i class="fas fa-book me-1"></i> Semua Buku
                    </a>
                </li>
                @else
                {{-- Menu User Biasa --}}
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('buku') ? 'active' : '' }}"
                        href="{{ route('buku.index') }}">
                        <i class="fas fa-book me-1"></i> Koleksi Buku
                    </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('buku/cari') ? 'active' : '' }}"
                        href="{{ route('buku.search') }}">
                        <i class="fas fa-search me-1"></i> Cari Buku
                    </a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                        href="{{ route('user.search') }}">
                        <i class="fas fa-users me-1"></i> Cari Pengguna
                    </a>
                </li>
                @endif
                @endauth
            </ul>

            <div class="d-flex align-items-center gap-3">
                @auth
                {{-- Badge admin --}}
                @if(auth()->user()->isAdmin())
                <span class="badge rounded-pill px-3 py-1 d-none d-sm-inline"
                    style="background-color: var(--amber, #C4894A); font-size: 0.68rem;">
                    <i class="fas fa-shield-alt me-1"></i> Admin
                </span>
                @endif

                <div class="text-end d-none d-sm-block">
                    <small class="text-muted d-block" style="font-size: 0.65rem;">Halo,</small>
                    <span class="fw-bold" style="font-size: 0.82rem;">{{ auth()->user()->name }}</span>
                </div>

                <div class="dropdown">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=C4894A&color=fff' }}"
                        class="rounded-circle dropdown-toggle"
                        style="cursor:pointer; width:34px; height:34px; object-fit:cover;"
                        data-bs-toggle="dropdown" alt="{{ auth()->user()->name }}">
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="min-width: 200px;">
                        <li class="px-3 py-2">
                            <div class="fw-bold small">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2 text-muted"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger py-2" type="submit">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                {{-- Belum login: utama ke halaman masuk --}}
                <a href="{{ route('login') }}" class="btn rounded-pill px-3 text-white"
                    style="background-color: var(--amber, #C4894A); border: none; font-size: 0.85rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-3 text-muted border"
                    style="font-size: 0.85rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>