@extends('layouts.app')
@section('title', 'Cari Buku')

@section('content')
<style>
    .search-input {
        border: 2px solid var(--border, #dee2e6);
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        transition: border-color 0.2s;
        background: white;
    }
    .search-input:focus {
        border-color: var(--amber, #C4894A);
        box-shadow: 0 0 0 0.25rem rgba(196,137,74,0.1);
        outline: none;
    }
    .btn-search {
        border-radius: 50px;
        padding: 0.75rem 2rem;
        background-color: var(--amber, #C4894A);
        color: white;
        border: none;
        font-weight: 600;
    }
    .btn-search:hover { background-color: #b07840; color: white; }

    /* Book card */
    .book-card {
        border: 1px solid var(--border, #eee);
        border-radius: 16px;
        transition: all 0.2s ease;
        overflow: hidden;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--amber, #C4894A);
        color: inherit;
        text-decoration: none;
    }
    .book-cover-ratio {
        position: relative;
        width: 100%;
        padding-top: 150%; /* mendekati 9:16 */
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
        font-size: 0.8rem;
        font-weight: 600;
        padding: 1rem;
        text-align: center;
        color: white;
    }
    .badge-incollection {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: var(--amber, #C4894A);
        color: white;
        border-radius: 50px;
        padding: 2px 8px;
        font-size: 0.65rem;
        font-weight: 600;
        z-index: 2;
    }
</style>

<div class="mb-5">
    <h3 class="fw-bold mb-1" style="color: var(--ink);">Cari Buku</h3>
    <p class="text-muted">Temukan buku dari jutaan koleksi Google Books.</p>

    <form action="{{ route('buku.search') }}" method="GET" class="d-flex gap-2 mt-3">
        <input type="text" name="q" class="search-input flex-grow-1"
               value="{{ $query }}"
               placeholder="Cari judul, penulis, atau ISBN..."
               autofocus>
        <button type="submit" class="btn btn-search">
            <i class="fas fa-search me-2"></i>Cari
        </button>
    </form>
</div>

@if($query && count($results) === 0)
    <div class="text-center py-5">
        <i class="fas fa-book-open fs-1 text-muted mb-3"></i>
        <p class="text-muted">Tidak ada buku ditemukan untuk "<strong>{{ $query }}</strong>".</p>
        <p class="text-muted small">Coba kata kunci lain atau cari dalam bahasa Inggris.</p>
    </div>
@elseif(count($results) > 0)
    <p class="text-muted small mb-4">Menampilkan {{ count($results) }} hasil untuk "<strong>{{ $query }}</strong>"</p>

    <div class="row g-3">
        @foreach($results as $book)
        @php
            $palettes = ['#5C6BC0','#26A69A','#EF5350','#AB47BC','#42A5F5','#66BB6A','#FFA726','#8D6E63'];
            $color = $palettes[crc32($book['google_books_id']) % count($palettes)];
        @endphp
        <div class="col-6 col-md-3 col-lg-2">
            <a href="{{ route('buku.detail', $book['google_books_id']) }}" class="book-card bg-white shadow-sm h-100">
                <div class="position-relative">
                    <div class="book-cover-ratio">
                        @if($book['cover_url'])
                            <img src="{{ $book['cover_url'] }}" alt="{{ $book['judul'] }}">
                        @else
                            <div class="cover-placeholder" style="background-color: {{ $color }}">
                                <i class="fas fa-book mb-2" style="font-size:1.5rem; opacity:0.5;"></i>
                                <span>{{ Str::limit($book['judul'], 40) }}</span>
                            </div>
                        @endif
                    </div>
                    @if($book['in_collection'])
                        <div class="badge-incollection">✓ Koleksiku</div>
                    @endif
                </div>
                <div class="p-2 pb-3">
                    <div class="fw-bold small text-truncate">{{ $book['judul'] }}</div>
                    @if($book['penulis'])
                        <div class="text-muted" style="font-size:0.75rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $book['penulis'] }}
                        </div>
                    @endif
                    @if($book['tahun_terbit'])
                        <div class="text-muted" style="font-size:0.7rem;">{{ $book['tahun_terbit'] }}</div>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
@else
    {{-- Empty state awal --}}
    <div class="text-center py-5">
        <i class="fas fa-search fs-1 text-muted mb-3" style="opacity:0.3;"></i>
        <p class="text-muted">Ketik judul atau nama penulis di atas untuk mulai mencari.</p>
    </div>
@endif
@endsection