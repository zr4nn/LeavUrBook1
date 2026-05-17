@extends('layouts.app')
@section('title', 'Profil ' . $user->name)

@section('content')
<style>
    .text-warning { color: var(--amber, #C4894A) !important; }
    .stat-pill {
        background: var(--warm-white, #f8f8f8);
        border: 1px solid var(--border, #eee);
        border-radius: 50px;
        padding: 0.4rem 1.25rem;
        font-size: 0.8rem;
        text-align: center;
        min-width: 90px;
    }
    .book-cover-ratio {
        position: relative;
        width: 100%;
        padding-top: 150%;
    }
    .book-cover-ratio > * {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
    .book-cover-ratio img { object-fit: cover; }
    .cover-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        text-align: center;
        color: white;
        font-weight: 700;
        font-size: 0.8rem;
        line-height: 1.3;
    }
    .hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important; }
    .transition-all { transition: all 0.2s ease; }
    .filter-pill {
        border-radius: 50px;
        padding: 0.3rem 0.9rem;
        font-size: 0.78rem;
        border: 1px solid var(--border, #dee2e6);
        background: white;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        color: var(--ink, #333);
        display: inline-block;
    }
    .filter-pill:hover, .filter-pill.active {
        background-color: var(--amber, #C4894A);
        color: white !important;
        border-color: var(--amber, #C4894A);
    }
    .section-tab {
        border: none;
        background: none;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: #adb5bd;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: all 0.15s;
    }
    .section-tab.active {
        color: var(--amber, #C4894A);
        border-bottom-color: var(--amber, #C4894A);
    }
    .section-tab:hover { color: var(--ink); }
    .heart-icon { color: #e74c3c; font-size: 0.8rem; }
</style>

{{-- HEADER PROFIL --}}
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
             class="rounded-circle flex-shrink-0"
             style="width:100px; height:100px; object-fit:cover; border:4px solid white; box-shadow:0 4px 16px rgba(0,0,0,0.12);">

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
                <span><i class="fas fa-calendar-alt me-1"></i> Bergabung {{ $user->created_at->format('d F Y') }}</span>
            </div>
            @if($user->bio)
                <p class="text-muted small mb-0" style="font-style:italic;">"{{ $user->bio }}"</p>
            @endif
        </div>
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
        <div class="stat-pill">
            <div class="fw-bold" style="color:#e74c3c;">{{ $stats['favorit'] }}</div>
            <div class="text-muted" style="font-size:0.7rem;">Favorit</div>
        </div>
    </div>
</div>

{{-- TABS: Koleksi / Favorit --}}
<div class="border-bottom mb-4">
    <button class="section-tab active" id="tabKoleksi" onclick="switchSection('koleksi')">
        <i class="fas fa-book me-1"></i> Koleksi Buku
    </button>
    <button class="section-tab" id="tabFavorit" onclick="switchSection('favorit')">
        <i class="fas fa-heart me-1" style="color:#e74c3c;"></i> Favorit
        @if($stats['favorit'] > 0)
            <span class="badge rounded-pill ms-1" style="background:#e74c3c; font-size:0.65rem;">{{ $stats['favorit'] }}</span>
        @endif
    </button>
</div>

{{-- SECTION KOLEKSI --}}
<div id="sectionKoleksi">
    {{-- Filter --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <span class="filter-pill active" onclick="filterBooks('semua', this)" style="cursor:pointer;">Semua</span>
        <span class="filter-pill" onclick="filterBooks('Sedang Dibaca', this)" style="cursor:pointer;">Sedang Dibaca</span>
        <span class="filter-pill" onclick="filterBooks('Selesai Dibaca', this)" style="cursor:pointer;">Selesai Dibaca</span>
        <span class="filter-pill" onclick="filterBooks('Daftar Tunggu', this)" style="cursor:pointer;">Daftar Tunggu</span>
    </div>

    <div class="row g-3" id="bookGrid">
        @forelse($userBooks as $ub)
        @php
            $book = $ub->book;
            $palettes = ['#5C6BC0','#26A69A','#EF5350','#AB47BC','#42A5F5','#66BB6A','#FFA726','#8D6E63'];
            $color = $palettes[crc32($book->google_books_id) % count($palettes)];
        @endphp
        <div class="col-6 col-md-4 col-lg-3 book-item" data-status="{{ $ub->status }}">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-4 overflow-hidden">
                <a href="{{ route('buku.detail', $book->google_books_id) }}" class="text-decoration-none text-dark">
                    <div class="position-relative">
                        <span class="position-absolute top-0 end-0 m-2 badge rounded-pill z-3
                            {{ $ub->status == 'Selesai Dibaca' ? 'bg-success' : ($ub->status == 'Sedang Dibaca' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                            style="font-size:0.65rem;">
                            {{ $ub->status }}
                        </span>
                        @if($ub->is_favorite)
                            <span class="position-absolute top-0 start-0 m-2 z-3">
                                <i class="fas fa-heart heart-icon"></i>
                            </span>
                        @endif
                        <div class="book-cover-ratio">
                            @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                            @else
                                <div class="cover-placeholder" style="background-color: {{ $color }}">
                                    <i class="fas fa-book mb-1" style="font-size:1.5rem; opacity:0.4;"></i>
                                    {{ Str::limit($book->judul, 50) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <h6 class="fw-bold text-truncate mb-1" style="font-size:0.875rem;">{{ $book->judul }}</h6>
                        <p class="text-muted mb-1" style="font-size:0.75rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $book->penulis ?? '-' }}
                        </p>
                        @if($ub->rating)
                            <div class="text-warning" style="font-size:0.75rem;">
                                @for($i=1;$i<=5;$i++)<span>{{ $i<=$ub->rating?'★':'☆' }}</span>@endfor
                            </div>
                        @endif
                        @if($ub->ulasan)
                            <p class="text-muted mt-1 mb-0" style="font-size:0.7rem; font-style:italic; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                "{{ $ub->ulasan }}"
                            </p>
                        @endif
                    </div>
                </a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-book-open fs-1 text-muted mb-3" style="opacity:0.3;"></i>
            <p class="text-muted">{{ $user->name }} belum punya koleksi buku.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- SECTION FAVORIT --}}
<div id="sectionFavorit" class="d-none">
    <div class="row g-3">
        @forelse($favoriteBooks as $ub)
        @php
            $book = $ub->book;
            $palettes = ['#5C6BC0','#26A69A','#EF5350','#AB47BC','#42A5F5','#66BB6A','#FFA726','#8D6E63'];
            $color = $palettes[crc32($book->google_books_id) % count($palettes)];
        @endphp
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-4 overflow-hidden">
                <a href="{{ route('buku.detail', $book->google_books_id) }}" class="text-decoration-none text-dark">
                    <div class="position-relative">
                        <span class="position-absolute top-0 start-0 m-2 z-3">
                            <i class="fas fa-heart heart-icon"></i>
                        </span>
                        <div class="book-cover-ratio">
                            @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                            @else
                                <div class="cover-placeholder" style="background-color: {{ $color }}">
                                    <i class="fas fa-book mb-1" style="font-size:1.5rem; opacity:0.4;"></i>
                                    {{ Str::limit($book->judul, 50) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <h6 class="fw-bold text-truncate mb-1" style="font-size:0.875rem;">{{ $book->judul }}</h6>
                        <p class="text-muted mb-1" style="font-size:0.75rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $book->penulis ?? '-' }}
                        </p>
                        @if($ub->rating)
                            <div class="text-warning" style="font-size:0.75rem;">
                                @for($i=1;$i<=5;$i++)<span>{{ $i<=$ub->rating?'★':'☆' }}</span>@endfor
                            </div>
                        @endif
                    </div>
                </a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-heart fs-1 mb-3" style="opacity:0.2; color:#e74c3c;"></i>
            <p class="text-muted">{{ $user->name }} belum punya buku favorit.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    function switchSection(section) {
        const isKoleksi = section === 'koleksi';
        document.getElementById('sectionKoleksi').classList.toggle('d-none', !isKoleksi);
        document.getElementById('sectionFavorit').classList.toggle('d-none', isKoleksi);
        document.getElementById('tabKoleksi').classList.toggle('active', isKoleksi);
        document.getElementById('tabFavorit').classList.toggle('active', !isKoleksi);
    }

    function filterBooks(status, btn) {
        document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.book-item').forEach(item => {
            item.style.display = (status === 'semua' || item.dataset.status === status) ? '' : 'none';
        });
    }
</script>
@endsection