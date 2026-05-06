@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<style>
    .table th { font-size: 0.75rem; text-transform: uppercase; color: #6c757d; font-weight: 600; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--ink);">Manajemen User</h3>
        <p class="text-muted small mb-0">Total {{ $users->count() }} user terdaftar</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill px-4 text-muted">
        <i class="fas fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 small py-2 mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger rounded-3 small py-2 mb-4">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Bergabung</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-muted small">{{ $index + 1 }}</td>
                    <td class="fw-bold small">{{ $user->name }}</td>
                    <td class="text-muted small">{{ $user->username ?? '-' }}</td>
                    <td class="text-muted small">{{ $user->email }}</td>
                    <td class="text-muted small">{{ $user->phone ?? '-' }}</td>
                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        {{-- Form ganti role --}}
                        <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select form-select-sm rounded-pill"
                                    style="width: auto; font-size: 0.75rem;"
                                    onchange="this.form.submit()">
                                <option value="user"  {{ $user->role == 'user'  ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada user terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection