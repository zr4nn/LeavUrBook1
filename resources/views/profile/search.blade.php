@extends('layouts.app')
@section('title', 'Cari Pengguna')

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
    .user-card {
        border: 1px solid var(--border, #eee);
        border-radius: 16px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .user-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--amber, #C4894A);
        color: inherit;
    }
    .stat-chip {
        background: var(--warm-white, #f5f5f5);
        border-radius: 50px;
        padding: 0.2rem 0.7rem;
        font-size: 0.7rem;
        color: #6c757d;
    }
</style>

<div class="mb-5">
    <h3 class="fw-bold mb-1" style="color: var(--ink);">Cari Pengguna</h3>
    <p class="text-muted">Temukan pengguna lain dan lihat koleksi bacaan mereka.</p>

    <form action="{{ route('user.search') }}" method="GET" class="d-flex gap-2 mt-3">
        <input type="text" name="q" class="search-input flex-grow-1"
               value="{{ $query }}"
               placeholder="Cari nama atau username..."
               autofocus>
        <button type="submit" class="btn btn-search">
            <i class="fas fa-search me-2"></i>Cari
        </button>
    </form>
</div>

@if($query && $users->count() === 0)
    <div class="text-center py-5">
        <i class="fas fa-user-slash fs-1 text-muted mb-3" style="opacity:0.3;"></i>
        <p class="text-muted">Tidak ada pengguna ditemukan untuk "<strong>{{ $query }}</strong>".</p>
    </div>
@elseif($users->count() > 0)
    <p class="text-muted small mb-4">{{ $users->count() }} pengguna ditemukan</p>
    <div class="row g-3">
        @foreach($users as $user)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('profile.public', $user->id) }}" class="user-card bg-white shadow-sm p-4">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="rounded-circle flex-shrink-0"
                         style="width:56px; height:56px; object-fit:cover; border: 3px solid var(--border,#eee);">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-truncate">{{ $user->name }}</div>
                        @if($user->username)
                            <div class="text-muted small">{{ $user->username }}</div>
                        @endif
                        @if($user->bio)
                            <div class="text-muted mt-1" style="font-size:0.75rem; font-style:italic;
                                display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden;">
                                "{{ $user->bio }}"
                            </div>
                        @endif
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <div class="d-flex gap-2 flex-wrap">
                    <span class="stat-chip"><i class="fas fa-book me-1"></i>{{ $user->user_books_count }} buku</span>
                    <span class="stat-chip"><i class="fas fa-check me-1"></i>{{ $user->selesai_count }} selesai</span>
                    <span class="stat-chip"><i class="fas fa-calendar me-1"></i>{{ $user->created_at->format('M Y') }}</span>
                </div>
            </a>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-users fs-1 text-muted mb-3" style="opacity:0.3;"></i>
        <p class="text-muted">Ketik nama atau username untuk mencari pengguna.</p>
    </div>
@endif
@endsection