@extends('layouts.landing')

@section('title', 'Beranda')

@section('content')
<section class="landing-hero py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center gap-2 mb-3" style="color: var(--ink, #1C1810);">
                    <img src="{{ asset('images/WhatsApp_Image_2026-05-07_at_08.14.07-removebg-preview.png') }}"
                        alt="LeavUrBook" width="40" height="40"
                        style="display:block; width:40px; height:40px; object-fit:cover; border-radius:12px;">
                    <span class="font-display fw-semibold">LeavUrBook</span>
                </div>
                <p class="text-uppercase small fw-semibold tracking-wide mb-2" style="letter-spacing: 0.12em; color: var(--amber);">
                    Koleksi pribadi & komunitas pembaca
                </p>
                <h1 class="font-display display-5 fw-bold lh-sm mb-3">
                    Catat buku yang kamu <span class="text-warning">baca</span>, dalam satu tempat yang nyaman.
                </h1>
                <p class="lead text-muted mb-4" style="font-size: 1.1rem;">
                    LeavUrBook membantu kamu menambahkan buku dari Google Books, memberi penilaian, mengatur koleksi,
                    dan menemukan profil pembaca lain — tanpa ribet.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-lg btn-amber rounded-pill px-4 shadow-sm">
                                <i class="fa-solid fa-chart-line me-2"></i>Dashboard admin
                            </a>
                            <a href="{{ route('admin.books') }}" class="btn btn-lg btn-outline-ink rounded-pill px-4">
                                <i class="fa-solid fa-book me-2"></i>Semua buku
                            </a>
                        @else
                            <a href="{{ route('buku.index') }}" class="btn btn-lg btn-amber rounded-pill px-4 shadow-sm">
                                <i class="fa-solid fa-layer-group me-2"></i>Lihat koleksi saya
                            </a>
                            <a href="{{ route('buku.search') }}" class="btn btn-lg btn-outline-ink rounded-pill px-4">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>Cari buku baru
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-lg btn-amber rounded-pill px-4 shadow-sm">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk ke akun
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-lg btn-outline-ink rounded-pill px-4">
                            <i class="fa-solid fa-user-plus me-2"></i>Belum punya akun? Daftar
                        </a>
                    @endauth
                </div>
                <div class="d-flex flex-wrap gap-4 small text-muted">
                    <div><i class="fa-solid fa-check text-success me-1"></i> Integrasi Google Books</div>
                    <div><i class="fa-solid fa-check text-success me-1"></i> Profil & koleksi publik</div>
                    <div><i class="fa-solid fa-check text-success me-1"></i> Pencarian pengguna</div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card p-4 p-md-5 position-relative overflow-hidden" style="isolation: isolate;">

                    <div class="position-relative" style="z-index: 1;">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-star"></i></div>
                            <div>
                                <div class="fw-semibold mb-1">Penilaian & catatan</div>
                                <p class="small text-muted mb-0">Tandai buku yang sudah dibaca dan beri bintang agar mudah diingat.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-globe"></i></div>
                            <div>
                                <div class="fw-semibold mb-1">Data dari Google Books</div>
                                <p class="small text-muted mb-0">Judul, sampul, dan metadata lengkap tanpa mengetik manual.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon flex-shrink-0"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <div class="fw-semibold mb-1">Temukan pembaca lain</div>
                                <p class="small text-muted mb-0">Jelajahi profil publik dan lihat apa yang mereka koleksi.</p>
                            </div>
                        </div>
                        <hr class="my-4 opacity-25">
                        <div class="row text-center g-3 small">
                            <div class="col-4">
                                <div class="font-display fs-4 fw-bold text-warning">1</div>
                                <div class="text-muted">Masuk</div>
                            </div>
                            <div class="col-4">
                                <div class="font-display fs-4 fw-bold text-warning">2</div>
                                <div class="text-muted">Cari & tambah buku</div>
                            </div>
                            <div class="col-4">
                                <div class="font-display fs-4 fw-bold text-warning">3</div>
                                <div class="text-muted">Bagikan koleksi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-top" style="border-color: var(--border) !important; background: var(--warm-white);">
    <div class="container py-lg-3">
        <div class="text-center mb-5">
            <h2 class="font-display fw-bold mb-2">Dibuat untuk pembaca sehari-hari</h2>
            <p class="text-muted mb-0 mx-auto" style="max-width: 36rem;">
                Fokus pada pengalaman yang simpel: kamu yang mengatur rak digitalmu, bukan sebaliknya.
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4 border bg-white" style="border-color: var(--border) !important; box-shadow: var(--shadow);">
                    <div class="feature-icon mb-3"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <h3 class="h5 fw-semibold mb-2">Pencarian cepat</h3>
                    <p class="small text-muted mb-0">Temukan judul lewat Google Books dan langsung simpan ke koleksi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4 border bg-white" style="border-color: var(--border) !important; box-shadow: var(--shadow);">
                    <div class="feature-icon mb-3"><i class="fa-solid fa-bookmark"></i></div>
                    <h3 class="h5 fw-semibold mb-2">Koleksi terpusat</h3>
                    <p class="small text-muted mb-0">Satu daftar untuk buku yang sedang atau sudah kamu baca.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4 border bg-white" style="border-color: var(--border) !important; box-shadow: var(--shadow);">
                    <div class="feature-icon mb-3"><i class="fa-solid fa-user-circle"></i></div>
                    <h3 class="h5 fw-semibold mb-2">Profil kamu</h3>
                    <p class="small text-muted mb-0">Bio, avatar, dan halaman publik agar orang lain bisa mengenal selera bacamu.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-lg-2">
        <div class="row align-items-center g-4 rounded-4 p-4 p-lg-5 border mx-1 mx-lg-0"
            style="background: linear-gradient(135deg, rgba(122, 140, 114, 0.18) 0%, rgba(196, 137, 74, 0.15) 100%); border-color: var(--border) !important;">
            <div class="col-lg-8">
                <h2 class="font-display fw-bold mb-2">Siap mengisi rak digitalmu?</h2>
                <p class="text-muted mb-0 mb-lg-0">
                    Masuk dengan akunmu untuk mengelola koleksi. Pengguna baru bisa daftar dari halaman masuk.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-lg btn-amber rounded-pill px-5 shadow-sm w-100 w-lg-auto">
                        Masuk
                    </a>
                @else
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-lg btn-amber rounded-pill px-5 shadow-sm w-100 w-lg-auto">
                            Dashboard admin
                        </a>
                    @else
                        <a href="{{ route('buku.search') }}" class="btn btn-lg btn-amber rounded-pill px-5 shadow-sm w-100 w-lg-auto">
                            Tambah buku
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection
