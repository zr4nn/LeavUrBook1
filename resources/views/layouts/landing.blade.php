<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LeavUrBook — catat, koleksi, dan bagikan buku favoritmu. Cari dari Google Books, beri rating, dan temukan pembaca lain.">
    <title>LeavUrBook — @yield('title', 'Beranda')</title>

    <link rel="icon" href="{{ asset('images/WhatsApp_Image_2026-05-07_at_08.14.07-removebg-preview.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700;1,9..144,500&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cream: #F5F0E8;
            --cream-dark: #EDE6D6;
            --warm-white: #FAF8F4;
            --ink: #1C1810;
            --ink-soft: #3D3629;
            --ink-muted: #7A7060;
            --amber: #C4894A;
            --amber-light: #E8B87A;
            --sage: #7A8C72;
            --sage-light: #A8B8A0;
            --blush: #D4B8A8;
            --border: rgba(28, 24, 16, 0.10);
            --shadow: 0 4px 24px rgba(28, 24, 16, 0.08);
        }

        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'Source Sans 3', system-ui, sans-serif;
        }

        .font-display {
            font-family: 'Fraunces', Georgia, serif;
        }

        .navbar {
            background-color: var(--warm-white) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid var(--border);
        }

        .landing-hero {
            background: radial-gradient(120% 80% at 70% 10%, rgba(232, 184, 122, 0.35) 0%, transparent 55%),
                radial-gradient(90% 60% at 10% 90%, rgba(168, 184, 160, 0.35) 0%, transparent 50%),
                linear-gradient(180deg, var(--warm-white) 0%, var(--cream) 100%);
            border-bottom: 1px solid var(--border);
        }

        .hero-card {
            background: var(--warm-white);
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(196, 137, 74, 0.15);
            color: var(--amber);
        }

        .landing-footer {
            background: var(--ink);
            color: rgba(250, 248, 244, 0.85);
        }

        .landing-footer a {
            color: var(--amber-light);
            text-decoration: none;
        }

        .landing-footer a:hover {
            color: #fff;
        }

        .btn-amber {
            background-color: var(--amber);
            border-color: var(--amber);
            color: #fff;
        }

        .btn-amber:hover {
            background-color: var(--ink-soft);
            border-color: var(--ink-soft);
            color: #fff;
        }

        .btn-outline-ink {
            border-color: rgba(28, 24, 16, 0.25);
            color: var(--ink-soft);
        }

        .btn-outline-ink:hover {
            background: var(--ink);
            border-color: var(--ink);
            color: var(--warm-white);
        }

        .text-warning {
            color: var(--amber) !important;
        }
    </style>
</head>

<body>
    @include('layouts.partials.navbar')

    @yield('content')

    <footer class="landing-footer py-5 mt-0">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="d-inline-flex align-items-center gap-2 font-display fs-5 fw-semibold mb-2"
                        style="color: rgba(250, 248, 244, 0.95);">
                        <img src="{{ asset('images/WhatsApp_Image_2026-05-07_at_08.14.07-removebg-preview.png') }}"
                            alt="LeavUrBook" width="30" height="30"
                            style="display:block; width:30px; height:30px; object-fit:cover; border-radius:10px;">
                        <span>LeavUrBook</span>
                    </div>
                    <p class="small mb-0 opacity-75">Ruang kecil untuk buku yang kamu baca, simpan, dan ceritakan.</p>
                </div>
                <div class="col-md-6 text-md-end small">
                    <div class="d-flex flex-wrap gap-3 justify-content-md-end mb-2">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">Dashboard admin</a>
                                <a href="{{ route('admin.books') }}">Semua buku</a>
                            @else
                                <a href="{{ route('buku.index') }}">Koleksi</a>
                                <a href="{{ route('buku.search') }}">Cari buku</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}">Masuk</a>
                            <a href="{{ route('register') }}">Daftar</a>
                        @endauth
                    </div>
                    <span class="opacity-50">&copy; {{ date('Y') }} LeavUrBook</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
