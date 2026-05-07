@extends('layouts.app')
@section('title', $book->judul)

@section('content')
<style>
    .form-control, .form-select {
        background-color: var(--warm-white, #fafafa);
        border: 1px solid var(--border, #dee2e6);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--amber, #C4894A);
        box-shadow: 0 0 0 0.25rem rgba(196,137,74,0.1);
        background-color: white;
    }
    .book-cover {
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        aspect-ratio: 2/3;
        object-fit: cover;
    }
    .cover-placeholder-detail {
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        aspect-ratio: 2/3;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        padding: 1.5rem;
        text-align: center;
    }
    .star-btn {
        background: none;
        border: none;
        font-size: 1.75rem;
        cursor: pointer;
        color: #dee2e6;
        transition: color 0.15s, transform 0.15s;
        padding: 0 4px;
    }
    .star-btn:hover, .star-btn.active { color: var(--amber, #C4894A); transform: scale(1.2); }
    .review-card {
        border: 1px solid var(--border, #eee);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
    .stat-badge {
        background: var(--warm-white, #f5f5f5);
        border: 1px solid var(--border, #eee);
        border-radius: 50px;
        padding: 0.3rem 0.9rem;
        font-size: 0.78rem;
    }
    .btn-amber { background-color: var(--amber, #C4894A); color: white; border: none; }
    .btn-amber:hover { background-color: #b07840; color: white; }
    .section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .user-link {
        color: var(--amber, #C4894A);
        text-decoration: none;
        font-weight: 600;
    }
    .user-link:hover { text-decoration: underline; }
</style>

@php
    $palettes = ['#5C6BC0','#26A69A','#EF5350','#AB47BC','#42A5F5','#66BB6A','#FFA726','#8D6E63'];
    $color = $palettes[crc32($book->google_books_id) % count($palettes)];
@endphp



<div class="row g-5">
    {{-- KOLOM KIRI: Cover + Aksi --}}
    <div class="col-md-3">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}" class="book-cover mb-3">
        @else
            <div class="cover-placeholder-detail mb-3" style="background-color: {{ $color }}">
                <i class="fas fa-book mb-2" style="font-size:2.5rem; opacity:0.4;"></i>
                <div style="font-weight:700; font-size:0.9rem;">{{ $book->judul }}</div>
            </div>
        @endif

        {{-- Statistik buku --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div class="stat-badge text-center">
                <div class="fw-bold">{{ $stats['total_koleksi'] }}</div>
                <div class="text-muted" style="font-size:0.65rem;">Di koleksi</div>
            </div>
            <div class="stat-badge text-center">
                <div class="fw-bold">{{ $stats['selesai_dibaca'] }}</div>
                <div class="text-muted" style="font-size:0.65rem;">Selesai baca</div>
            </div>
            @if($stats['rating_rata'])
            <div class="stat-badge text-center">
                <div class="fw-bold" style="color:var(--amber,#C4894A);">★ {{ number_format($stats['rating_rata'], 1) }}</div>
                <div class="text-muted" style="font-size:0.65rem;">Rating</div>
            </div>
            @endif
        </div>

        {{-- Tombol koleksi — hanya pengguna biasa --}}
        @unless(auth()->user()->isAdmin())
            @if($userBook)
                <div class="d-grid gap-2">
                    <span class="badge rounded-pill py-2
                        {{ $userBook->status == 'Selesai Dibaca' ? 'bg-success' : ($userBook->status == 'Sedang Dibaca' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                        {{ $userBook->status }}
                    </span>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill"
                            data-bs-toggle="collapse" data-bs-target="#formKoleksi">
                        <i class="fas fa-edit me-1"></i> Edit Koleksi
                    </button>
                    <form action="{{ route('buku.destroy', $book->google_books_id) }}" method="POST"
                          onsubmit="return confirm('Hapus dari koleksi?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill w-100">
                            <i class="fas fa-trash me-1"></i> Hapus dari Koleksi
                        </button>
                    </form>
                </div>
            @else
                <div class="d-grid">
                    <button class="btn btn-amber rounded-pill py-2"
                            data-bs-toggle="collapse" data-bs-target="#formKoleksi">
                        <i class="fas fa-plus me-2"></i> Tambah ke Koleksi
                    </button>
                </div>
            @endif
        @else
            <p class="small text-muted mb-0">
                Akun admin tidak memiliki koleksi pribadi. Kelola katalog lewat menu <strong>Semua Buku</strong>.
            </p>
        @endunless
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-9">
        @if($book->genre)
            <span class="badge bg-light text-muted border rounded-pill px-3 mb-2" style="font-size:0.75rem;">{{ $book->genre }}</span>
        @endif
        <h2 class="fw-bold mb-1" style="color: var(--ink);">{{ $book->judul }}</h2>
        @if($book->penulis)
            <p class="text-muted mb-1"><i class="fas fa-pen-nib me-1"></i> {{ $book->penulis }}</p>
        @endif
        <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
            @if($book->penerbit)<span><i class="fas fa-building me-1"></i>{{ $book->penerbit }}</span>@endif
            @if($book->tahun_terbit)<span><i class="fas fa-calendar me-1"></i>{{ $book->tahun_terbit }}</span>@endif
            @if($book->total_halaman)<span><i class="fas fa-file-alt me-1"></i>{{ $book->total_halaman }} halaman</span>@endif
            @if($book->isbn)<span><i class="fas fa-barcode me-1"></i>{{ $book->isbn }}</span>@endif
            @if($book->bahasa)<span><i class="fas fa-language me-1"></i>{{ strtoupper($book->bahasa) }}</span>@endif
        </div>

        @if($book->deskripsi)
            <div class="mb-4">
                <div class="section-title">Tentang Buku</div>
                <p class="text-muted" style="line-height:1.7; font-size:0.9rem;" id="descText">
                    {{ Str::limit(strip_tags($book->deskripsi), 300) }}
                </p>
                @if(strlen(strip_tags($book->deskripsi)) > 300)
                    <p class="text-muted d-none" id="descFull" style="line-height:1.7; font-size:0.9rem;">
                        {{ strip_tags($book->deskripsi) }}
                    </p>
                    <button class="btn btn-sm btn-link text-decoration-none p-0"
                            style="color: var(--amber, #C4894A);"
                            onclick="toggleDesc(event)">Baca selengkapnya</button>
                @endif
            </div>
        @endif

        {{-- FORM TAMBAH/EDIT KOLEKSI --}}
        @unless(auth()->user()->isAdmin())
        <div class="collapse" id="formKoleksi">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3">{{ $userBook ? 'Edit Koleksi' : 'Tambah ke Koleksi' }}</h6>
                <form action="{{ $userBook ? route('buku.update', $book->google_books_id) : route('buku.store') }}"
                      method="POST">
                    @csrf
                    @if($userBook) @method('PUT') @endif
                    @unless($userBook)
                        <input type="hidden" name="google_books_id" value="{{ $book->google_books_id }}">
                    @endunless

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="section-title">Status Baca</label>
                            <select name="status" class="form-select rounded-3">
                                @foreach(['Daftar Tunggu', 'Sedang Dibaca', 'Selesai Dibaca'] as $s)
                                    <option value="{{ $s }}" {{ ($userBook?->status ?? 'Daftar Tunggu') == $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="section-title">Halaman Saat Ini</label>
                            <input type="number" name="halaman_saat_ini" class="form-control rounded-3"
                                value="{{ $userBook?->halaman_saat_ini ?? 0 }}"
                                min="0" max="{{ $book->total_halaman ?? 99999 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="section-title">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control rounded-3"
                                value="{{ $userBook?->tanggal_mulai?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="section-title">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control rounded-3"
                                value="{{ $userBook?->tanggal_selesai?->format('Y-m-d') }}">
                        </div>
                        <div class="col-12" id="ratingSection"
                             style="{{ ($userBook?->status ?? '') !== 'Selesai Dibaca' ? 'display:none;' : '' }}">
                            <label class="section-title">Rating</label>
                            <div class="d-flex gap-1 align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-btn {{ ($userBook?->rating ?? 0) >= $i ? 'active' : '' }}"
                                            data-value="{{ $i }}" onclick="setRating({{ $i }})">★</button>
                                @endfor
                                <input type="hidden" name="rating" id="ratingInput" value="{{ $userBook?->rating }}">
                                <span class="text-muted small ms-2" id="ratingLabel">
                                    {{ $userBook?->rating ? $userBook->rating . '/5' : 'Belum dinilai' }}
                                </span>
                            </div>
                            <small class="text-muted mt-1 d-block">Rating hanya bisa diberikan setelah buku selesai dibaca.</small>
                        </div>
                        <div class="col-12">
                            <label class="section-title">Ulasan</label>
                            <textarea name="ulasan" class="form-control rounded-3" rows="4"
                                placeholder="Tuliskan ulasanmu..." maxlength="2000">{{ $userBook?->ulasan }}</textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-light rounded-pill px-4 me-2"
                                    data-bs-toggle="collapse" data-bs-target="#formKoleksi">Batal</button>
                            <button type="submit" class="btn btn-amber rounded-pill px-5">
                                <i class="fas fa-save me-2"></i>{{ $userBook ? 'Perbarui' : 'Tambah ke Koleksi' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endunless

        {{-- ULASAN USER LAIN --}}
        @if($reviews->count() > 0)
            <div class="section-title mt-2">Ulasan Pembaca ({{ $reviews->count() }})</div>
            @foreach($reviews as $review)
            <div class="review-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    {{-- Avatar bisa diklik ke profil --}}
                    <a href="{{ route('profile.public', $review->user->id) }}">
                        <img src="{{ $review->user->avatar_url }}" class="rounded-circle"
                             width="36" height="36" style="object-fit:cover;" alt="{{ $review->user->name }}">
                    </a>
                    <div>
                        {{-- Nama user bisa diklik ke profil --}}
                        <a href="{{ route('profile.public', $review->user->id) }}" class="user-link">
                            {{ $review->user->name }}
                        </a>
                        <div class="text-muted" style="font-size:0.7rem;">
                            @if($review->user->username)
                                {{ $review->user->username }} ·
                            @endif
                            {{ $review->updated_at->diffForHumans() }}
                        </div>
                    </div>
                    @if($review->rating)
                        <div class="ms-auto" style="color:var(--amber,#C4894A); font-size:0.85rem;">
                            @for($i=1;$i<=5;$i++)<span>{{ $i<=$review->rating?'★':'☆' }}</span>@endfor
                        </div>
                    @endif
                </div>
                <p class="mb-0 text-muted" style="font-size:0.875rem; line-height:1.6;">
                    "{{ $review->ulasan }}"
                </p>
            </div>
            @endforeach
        @endif

        {{-- Ulasan sendiri (hanya pengguna dengan koleksi) --}}
        @if($userBook?->ulasan && !auth()->user()->isAdmin())
            <div class="section-title mt-3">Ulasanmu</div>
            <div class="review-card" style="border-color: var(--amber, #C4894A); background: #fffaf5;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle"
                         width="36" height="36" style="object-fit:cover;">
                    <div>
                        <div class="fw-bold small">{{ auth()->user()->name }}</div>
                        @if($userBook->rating)
                            <div style="color:var(--amber,#C4894A); font-size:0.75rem;">
                                @for($i=1;$i<=5;$i++)<span>{{ $i<=$userBook->rating?'★':'☆' }}</span>@endfor
                            </div>
                        @endif
                    </div>
                </div>
                <p class="mb-0 text-muted" style="font-size:0.875rem; line-height:1.6;">"{{ $userBook->ulasan }}"</p>
            </div>
        @endif
    </div>
</div>

<script>
    function setRating(val) {
        document.getElementById('ratingInput').value = val;
        document.getElementById('ratingLabel').textContent = val + '/5';
        document.querySelectorAll('.star-btn').forEach((btn, idx) => {
            btn.classList.toggle('active', idx < val);
        });
    }

    // Tampilkan/sembunyikan rating berdasarkan status
    const statusSelect = document.querySelector('select[name="status"]');
    const ratingSection = document.getElementById('ratingSection');

    function toggleRating() {
        if (!statusSelect || !ratingSection) return;
        if (statusSelect.value === 'Selesai Dibaca') {
            ratingSection.style.display = '';
        } else {
            ratingSection.style.display = 'none';
            // Reset rating jika status bukan selesai
            document.getElementById('ratingInput').value = '';
            document.getElementById('ratingLabel').textContent = 'Belum dinilai';
            document.querySelectorAll('.star-btn').forEach(btn => btn.classList.remove('active'));
        }
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', toggleRating);
    }

    function toggleDesc(e) {
        const short = document.getElementById('descText');
        const full  = document.getElementById('descFull');
        if (full.classList.contains('d-none')) {
            full.classList.remove('d-none');
            short.classList.add('d-none');
            e.target.textContent = 'Sembunyikan';
        } else {
            full.classList.add('d-none');
            short.classList.remove('d-none');
            e.target.textContent = 'Baca selengkapnya';
        }
    }
</script>
@endsection