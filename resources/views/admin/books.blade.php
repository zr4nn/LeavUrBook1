@extends('layouts.app')
@section('title', 'Semua Buku')

@section('content')
<style>
    .table th { font-size: 0.75rem; text-transform: uppercase; color: #6c757d; font-weight: 600; }
    .book-thumb {
        width: 36px;
        height: 54px;
        object-fit: cover;
        border-radius: 6px;
    }
    .book-thumb-placeholder {
        width: 36px;
        height: 54px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Semua Buku</h3>
        <p class="text-muted small mb-0">Total {{ $books->count() }} buku dalam database</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill px-4 text-muted">
        <i class="fas fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 small py-2 mb-4">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Halaman</th>
                    <th>Di Koleksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $palettes = ['#5C6BC0','#26A69A','#EF5350','#AB47BC','#42A5F5','#66BB6A','#FFA726','#8D6E63'];
                @endphp
                @forelse($books as $index => $book)
                <tr>
                    <td class="text-muted small">{{ $index + 1 }}</td>
                    <td>
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" class="book-thumb" alt="{{ $book->judul }}">
                        @else
                            <div class="book-thumb-placeholder"
                                 style="background-color: {{ $palettes[crc32($book->google_books_id) % count($palettes)] }}">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold small" style="max-width:180px;">
                        <a href="{{ route('buku.detail', $book->google_books_id) }}"
                           class="text-decoration-none text-dark text-truncate d-block">
                            {{ $book->judul }}
                        </a>
                    </td>
                    <td class="small text-muted">{{ $book->penulis ?? '-' }}</td>
                    <td class="small text-muted">{{ $book->tahun_terbit ?? '-' }}</td>
                    <td class="small text-muted">{{ $book->total_halaman ?? '-' }}</td>
                    <td class="small text-muted">{{ $book->user_books_count ?? $book->userBooks()->count() }} user</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection