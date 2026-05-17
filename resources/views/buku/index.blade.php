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

    /* Tombol favorit */
    .btn-favorite {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
        z-index: 4;
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .btn-favorite:hover { transform: scale(1.15); background: white; }
    .btn-favorite.active { background: #fff0f0; }
    .btn-favorite .fa-heart { color: #ccc; }
    .btn-favorite.active .fa-heart { color: #e74c3c; }
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
                    {{-- Badge status --}}
                    <span class="position-absolute top-0 end-0 m-2 badge rounded-pill z-3
                        {{ $ub->status == 'Selesai Dibaca' ? 'bg-success' : ($ub->status == 'Sedang Dibaca' ? 'bg-warning text-dark' : 'bg-secondary') }}"
                        style="font-size:0.65rem;">
                        {{ $ub->status }}
                    </span>

                    {{-- Tombol Favorit --}}
                    <button class="btn-favorite {{ $ub->is_favorite ? 'active' : '' }}"
                            onclick="toggleFavorite(event, '{{ $book->google_books_id }}', this)"
                            title="{{ $ub->is_favorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                        <i class="fa-{{ $ub->is_favorite ? 'solid' : 'regular' }} fa-heart"></i>
                    </button>

                    <form id="favoriteForm-{{ $book->google_books_id }}"
                          action="{{ route('buku.favorite', $book->google_books_id) }}"
                          method="POST" class="d-none">
                        @csrf
                    </form>
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
                    <form action="{{ route('buku.destroy', $book->google_books_id) }}" method="POST"
                          class="flex-grow-1 m-0" id="deleteForm-{{ $book->google_books_id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill"
                                style="font-size:0.75rem;"
                                onclick="showDeleteModal('{{ $book->google_books_id }}', '{{ addslashes($book->judul) }}')">
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
{{-- MODAL KONFIRMASI HAPUS --}}
<div id="deleteModalOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9998; backdrop-filter:blur(2px);"
     onclick="closeDeleteModal()">
</div>
<div id="deleteModal"
     style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
            z-index:9999; width:100%; max-width:380px; padding: 0 1rem;">
    <div class="card border-0 shadow-lg rounded-4 p-4">
        <div class="text-center mb-3">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:56px; height:56px; background:#fff5f5;">
                <i class="fas fa-trash-alt text-danger" style="font-size:1.4rem;"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color:var(--ink);">Hapus dari Koleksi?</h5>
            <p class="text-muted small mb-0">
                Buku <strong id="deleteBookTitle"></strong> akan dihapus dari koleksimu.
            </p>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-light rounded-pill flex-grow-1 fw-medium"
                    onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn btn-danger rounded-pill flex-grow-1 fw-medium"
                    id="confirmDeleteBtn" onclick="submitDelete()">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
        </div>
    </div>
</div>

<script>
    // ── Delete Modal ──────────────────────────────────────────────
    let deleteTargetId = null;

    function showDeleteModal(googleBooksId, title) {
        deleteTargetId = googleBooksId;
        document.getElementById('deleteBookTitle').textContent = title;
        document.getElementById('deleteModalOverlay').style.display = 'block';
        document.getElementById('deleteModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteTargetId = null;
        document.getElementById('deleteModalOverlay').style.display = 'none';
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function submitDelete() {
        if (!deleteTargetId) return;
        document.getElementById('deleteForm-' + deleteTargetId).submit();
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });

    // ── Toggle Favorite (AJAX) ────────────────────────────────────
    function toggleFavorite(event, googleBooksId, btn) {
        event.preventDefault();
        event.stopPropagation();

        const form   = document.getElementById('favoriteForm-' + googleBooksId);
        const icon   = btn.querySelector('i');
        const csrfToken = form.querySelector('input[name="_token"]').value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.is_favorite) {
                btn.classList.add('active');
                icon.classList.replace('fa-regular', 'fa-solid');
                btn.title = 'Hapus dari favorit';
            } else {
                btn.classList.remove('active');
                icon.classList.replace('fa-solid', 'fa-regular');
                btn.title = 'Tambah ke favorit';
            }
        })
        .catch(err => console.error('Toggle favorite error:', err));
    }
</script>
@endsection