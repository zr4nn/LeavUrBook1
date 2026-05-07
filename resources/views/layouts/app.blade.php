<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeavUrBook - @yield('title')</title>
    
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

        .nav-link.active {
            font-weight: bold;
            color: var(--amber) !important;
        }

        /* Memastikan warna bintang mengikuti tema Amber */
        .text-warning {
            color: var(--amber) !important;
        }

        /* Pagination admin — pusat, pill, selaras tema */
        .admin-pagination {
            text-align: center;
        }

        .admin-pagination .pagination {
            gap: 0.35rem;
            margin-bottom: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .admin-pagination .page-link {
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--ink-soft);
            background-color: var(--warm-white);
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
            font-weight: 500;
            min-width: 2.35rem;
            text-align: center;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .admin-pagination .page-link:hover {
            background-color: var(--cream-dark);
            border-color: rgba(196, 137, 74, 0.45);
            color: var(--ink);
        }

        .admin-pagination .page-item.active .page-link {
            background-color: var(--amber);
            border-color: var(--amber);
            color: #fff;
        }

        .admin-pagination .page-item.active .page-link:hover {
            background-color: var(--ink-soft);
            border-color: var(--ink-soft);
            color: #fff;
        }

        .admin-pagination .page-item.disabled .page-link {
            background-color: var(--cream);
            border-color: var(--border);
            color: var(--ink-muted);
            opacity: 0.85;
        }
    </style>
</head>

<body>
    @include('layouts.partials.navbar')

    {{-- TOAST NOTIFIKASI GLOBAL — satu-satunya tempat notifikasi muncul --}}
    @if(session('success') || session('error') || session('info'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 70px;">
        <div id="globalToast" class="toast align-items-center border-0 shadow"
             role="alert" aria-live="assertive" aria-atomic="true"
             data-bs-autohide="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    @if(session('success'))
                        <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width:28px; height:28px; background:rgba(255,255,255,0.25);">
                            <i class="fas fa-check" style="font-size:0.75rem;"></i>
                        </span>
                        {{ session('success') }}
                    @elseif(session('error'))
                        <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width:28px; height:28px; background:rgba(255,255,255,0.25);">
                            <i class="fas fa-times" style="font-size:0.75rem;"></i>
                        </span>
                        {{ session('error') }}
                    @elseif(session('info'))
                        <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width:28px; height:28px; background:rgba(255,255,255,0.25);">
                            <i class="fas fa-info" style="font-size:0.75rem;"></i>
                        </span>
                        {{ session('info') }}
                    @endif
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <style>
        #globalToast {
            min-width: 280px;
            max-width: 360px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            @if(session('success'))
            background-color: var(--amber, #C4894A);
            color: white;
            @elseif(session('error'))
            background-color: #dc3545;
            color: white;
            @else
            background-color: var(--ink, #1C1810);
            color: white;
            @endif
        }
    </style>

    <main class="container py-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-show toast notifikasi
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('globalToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
        // Fungsi untuk mewarnai bintang
        function updateStars(parent, val) {
            parent.querySelectorAll('.star-icon').forEach((s, index) => {
                if (index < val) {
                    s.classList.remove('fa-regular', 'text-muted');
                    s.classList.add('fa-solid', 'text-warning');
                } else {
                    s.classList.remove('fa-solid', 'text-warning');
                    s.classList.add('fa-regular', 'text-muted');
                }
            });
        }

        // 1. Jalankan saat halaman baru dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star-input:checked').forEach(input => {
                let parent = input.closest('.d-flex');
                updateStars(parent, input.value);
            });
        });

        // 2. Jalankan saat bintang diklik
        document.querySelectorAll('.star-icon').forEach(star => {
            star.addEventListener('click', function() {
                let val = this.getAttribute('data-value');
                let parent = this.closest('.d-flex');

                let radio = parent.querySelector(`#editStar${val}`) || parent.querySelector(`#star${val}`);
                if (radio) radio.checked = true;

                updateStars(parent, val);
            });
        });
    </script>
</body>

</html>