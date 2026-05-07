@extends('layouts.app')
@section('title', 'Koleksi Bukuku')

@section('content')
<style>
    .hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important; }
    .transition-all { transition: all 0.2s ease; }
    .text-warning { color: var(--amber, #C4894A) !important; }
    .btn-outline-primary { color: var(--amber, #C4894A); border-color: var(--amber, #C4894A); }
    .btn-outline-primary:hover { background-color: var(--amber, #C4894A); color: white; border-color: var(--amber, #C4894A); }

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
        font-size: 0.85rem;
        line-height: 1.3;
    }
    .filter-pill {
        border-radius: 50px;
        padding: 0.35rem 1rem;
        font-size: 0.8rem;
        border: 1px solid var(--border, #dee2e6);
        background: white;
        cursor: pointer;
        transition: all 0.15s;
    }
    .filter-pill.active, .filter-pill:hover {
        background-color: var(--amber, #C4894A);
        color: white;
        border-color: var(--amber, #C4894A);
    }
</style>

{{-- STATS --}}
<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="text-muted small mb-1">Total Koleksi</h6>
            <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="text-muted small mb-1">Sedang Dibaca</h6>
            <h4 class="fw-bold mb-0 text-warning">{{ $stats['sedang_dibaca'] }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="text-muted small mb-1">Selesai Dibaca</h6>
            <h4 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h4>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="text-muted small mb-1">Daftar Tunggu</h6>
            <h4 class="fw-bold mb-0 text-secondary">{{ $stats['daftar_tunggu'] }}</h4>
        </div>
    </div>
</div>

<hr class="my-4 opacity-25">

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Koleksi Bukuku</h3>
        <p class="text-muted small mb-0">Semua buku yang sudah kamu tambahkan.</p>
    </div>
    <a href="{{ route('buku.search') }}"
       class="btn rounded-pill px-4 py-2 text-white"
       style="background-color: var(--amber, #C4894A); border: none;">
        <i class="fas fa-search me-2"></i> Cari Buku
    </a>
</div>

{{-- FILTER --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('buku.index', ['status' => 'semua']) }}" class="filter-pill text-decoration-none {{ $status === 'semua' ? 'active' : '' }}" style="color: inherit;">Semua</a>
    <a href="{{ route('buku.index', ['status' => 'Sedang Dibaca']) }}" class="filter-pill text-decoration-none {{ $status === 'Sedang Dibaca' ? 'active' : '' }}" style="color: inherit;">Sedang Dibaca</a>
    <a href="{{ route('buku.index', ['status' => 'Selesai Dibaca']) }}" class="filter-pill text-decoration-none {{ $status === 'Selesai Dibaca' ? 'active' : '' }}" style="color: inherit;">Selesai Dibaca</a>
    <a href="{{ route('buku.index', ['status' => 'Daftar Tunggu']) }}" class="filter-pill text-decoration-none {{ $status === 'Daftar Tunggu' ? 'active' : '' }}" style="color: inherit;">Daftar Tunggu</a>
</div>

{{-- GRID --}}
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

                <div class="card-body p-3 pb-1">
                    <h6 class="fw-bold text-truncate mb-1" style="font-size:0.875rem;">{{ $book->judul }}</h6>
                    <p class="text-muted mb-1" style="font-size:0.75rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $book->penulis ?? '-' }}
                    </p>
                    @if($ub->rating)
                        <div class="text-warning" style="font-size:0.75rem;">
                            @for($i=1;$i<=5;$i++)<span>{{ $i<=$ub->rating?'★':'☆' }}</span>@endfor
                        </div>
                    @endif
                    @if($ub->status == 'Sedang Dibaca' && $book->total_halaman)
                        <div class="mt-2">
                            <div class="progress rounded-pill" style="height:4px;">
                                <div class="progress-bar" style="width:{{ min(100, round(($ub->halaman_saat_ini/$book->total_halaman)*100)) }}%; background-color: var(--amber, #C4894A);"></div>
                            </div>
                            <small class="text-muted" style="font-size:0.65rem;">
                                {{ $ub->halaman_saat_ini }}/{{ $book->total_halaman }} hal.
                            </small>
                        </div>
                    @endif
                </div>
            </a>

            <div class="card-footer bg-white border-0 p-3 pt-1">
                <div class="d-flex gap-2">
                    <a href="{{ route('buku.detail', $book->google_books_id) }}"
                       class="btn btn-outline-primary btn-sm flex-grow-1 rounded-pill" style="font-size:0.75rem;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('buku.destroy', $book->google_books_id) }}" method="POST" class="flex-grow-1 m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill"
                                onclick="return confirm('Hapus dari koleksi?')" style="font-size:0.75rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-book-open fs-1 text-muted mb-3" style="opacity:0.3;"></i>
        <p class="text-muted">Koleksimu masih kosong.</p>
        <a href="{{ route('buku.search') }}" class="btn rounded-pill px-4 text-white"
           style="background-color: var(--amber, #C4894A); border:none;">
            <i class="fas fa-search me-2"></i> Cari Buku Sekarang
        </a>
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if($userBooks->total() > 0 && $userBooks->hasPages())
<div class="admin-pagination pt-3 mt-4">
    {{ $userBooks->links() }}
</div>
@endif
@endsection