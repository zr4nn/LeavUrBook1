<div class="sidebar w-64 d-none d-md-block p-3 vh-100 sticky-top">
    <div class="mb-4 p-2 text-center">
        <h4 class="fw-bold text-primary">LeavUrBook</h4>
    </div>
    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('/') ? 'active bg-primary text-white rounded' : 'text-muted' }}">
                <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('buku.index') }}" class="nav-link {{ request()->is('buku*') ? 'active bg-primary text-white rounded' : 'text-muted' }}">
                <i class="fas fa-book me-2"></i> Buku Saya
            </a>
        </li>
        <li class="nav-item mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>