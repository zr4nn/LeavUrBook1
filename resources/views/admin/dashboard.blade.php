@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<style>
    .stat-card { border-left: 4px solid var(--amber, #C4894A); }
    .table th { font-size: 0.75rem; text-transform: uppercase; color: #6c757d; font-weight: 600; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Admin Dashboard</h3>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <span class="badge rounded-pill px-3 py-2" style="background-color: var(--amber, #C4894A);">
        <i class="fas fa-shield-alt me-1"></i> Administrator
    </span>
</div>

{{-- STATS --}}
<div class="row g-4 mb-5">
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white stat-card">
            <h6 class="text-muted small mb-1">Total Buku</h6>
            <h3 class="fw-bold mb-0">{{ $stats['total_buku'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white stat-card" style="border-left-color:#42A5F5 !important;">
            <h6 class="text-muted small mb-1">Total User</h6>
            <h3 class="fw-bold mb-0">{{ $stats['total_user'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white stat-card" style="border-left-color:#ffc107 !important;">
            <h6 class="text-muted small mb-1">Sedang Dibaca</h6>
            <h3 class="fw-bold mb-0">{{ $stats['sedang_dibaca'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white stat-card" style="border-left-color:#66BB6A !important;">
            <h6 class="text-muted small mb-1">Selesai Dibaca</h6>
            <h3 class="fw-bold mb-0">{{ $stats['selesai_dibaca'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white stat-card" style="border-left-color:#78909C !important;">
            <h6 class="text-muted small mb-1">Daftar Tunggu</h6>
            <h3 class="fw-bold mb-0">{{ $stats['daftar_tunggu'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- RECENT USERS --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">User Terbaru</h5>
                <a href="{{ route('admin.users') }}" class="btn btn-sm rounded-pill px-3"
                   style="background-color: var(--amber, #C4894A); color: white; border:none;">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="fw-bold small">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $user->username ?? '-' }}</div>
                            </td>
                            <td class="small text-muted">{{ $user->email }}</td>
                            <td class="small text-muted">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted small">Belum ada user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RECENT BOOKS --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Buku Terbaru</h5>
                <a href="{{ route('admin.books') }}" class="btn btn-sm rounded-pill px-3"
                   style="background-color: var(--amber, #C4894A); color: white; border:none;">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Di Koleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBooks as $book)
                        <tr>
                            <td class="fw-bold small text-truncate" style="max-width:130px;">{{ $book->judul }}</td>
                            <td class="small text-muted">{{ $book->penulis ?? '-' }}</td>
                            <td class="small text-muted">{{ $book->userBooks()->count() }} user</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted small">Belum ada buku.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection