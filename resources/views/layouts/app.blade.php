<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeavUrBook - @yield('title')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
    </style>
</head>

<body>
    @include('layouts.partials.navbar')

    <main class="container py-5">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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