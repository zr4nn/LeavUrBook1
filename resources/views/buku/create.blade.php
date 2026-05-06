@extends('layouts.app')
@section('title', 'Tambah Buku Baru')

@section('content')
<style>
    .form-control,
    .form-select {
        background-color: var(--warm-white);
        border: 1px solid var(--border);
        color: var(--ink);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--amber);
        box-shadow: 0 0 0 0.25rem rgba(196, 137, 74, 0.1);
        background-color: white;
    }

    .star-icon {
        transition: transform 0.2s;
    }

    .star-icon:hover {
        transform: scale(1.2);
    }

    /* Cover Upload — rasio 9:16 */
    .cover-upload-wrapper {
        width: 100%;
        max-width: 200px; /* batas lebar agar tidak terlalu besar */
    }

    .cover-upload-ratio {
        position: relative;
        width: 100%;
        padding-top: 177.78%; /* 16/9 * 100 = 177.78% → rasio 9:16 */
    }

    .cover-upload-area {
        position: absolute;
        inset: 0;
        border: 2px dashed var(--border, #dee2e6);
        border-radius: 12px;
        background-color: var(--warm-white, #fafafa);
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .cover-upload-area:hover {
        border-color: var(--amber, #C4894A);
        background-color: rgba(196, 137, 74, 0.04);
    }

    .cover-upload-area.has-image {
        border-style: solid;
        border-color: var(--amber, #C4894A);
    }

    .cover-upload-area img#cover-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
        position: absolute;
        inset: 0;
    }

    .cover-upload-area.has-image img#cover-preview {
        display: block;
    }

    .cover-upload-placeholder {
        text-align: center;
        padding: 1rem;
        z-index: 1;
    }

    .cover-upload-area.has-image .cover-upload-placeholder {
        display: none;
    }

    .cover-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 2;
    }

    .cover-upload-area.has-image:hover .cover-overlay {
        display: flex;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 rounded-4" style="background: white; border: 1px solid var(--border) !important;">
            <h4 class="fw-bold mb-4" style="color: var(--ink);">Tambah Koleksi Buku</h4>

            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    {{-- Cover Upload 9:16 --}}
                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Cover Buku</label>
                        <div class="cover-upload-wrapper">
                            <div class="cover-upload-ratio">
                                <div class="cover-upload-area" id="coverUploadArea"
                                     onclick="document.getElementById('cover_input').click()">
                                    <img id="cover-preview" src="" alt="Preview Cover">
                                    <div class="cover-overlay">
                                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3"
                                                onclick="event.stopPropagation(); document.getElementById('cover_input').click()">
                                            <i class="fas fa-camera me-1"></i> Ganti
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                                onclick="event.stopPropagation(); removeCover()">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="cover-upload-placeholder">
                                        <i class="fas fa-image fs-1 mb-2" style="color: var(--border, #ccc);"></i>
                                        <p class="mb-0 small text-muted">Klik untuk upload cover</p>
                                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">JPG, PNG, WEBP — maks. 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="file" id="cover_input" name="cover" class="d-none" accept="image/jpeg,image/png,image/webp">
                        @error('cover')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Judul Buku</label>
                        <input type="text" name="judul" class="form-control rounded-3 py-2"
                            value="{{ old('judul') }}" placeholder="Contoh: Laskar Pelangi" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Penulis</label>
                        <input type="text" name="penulis" class="form-control rounded-3 py-2"
                            value="{{ old('penulis') }}" placeholder="Nama penulis" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Status Baca</label>
                        <select name="status" class="form-select rounded-3 py-2">
                            @foreach(['Daftar Tunggu', 'Sedang Dibaca', 'Selesai Dibaca'] as $s)
                                <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Halaman Saat Ini</label>
                        <input type="number" name="halaman_saat_ini" class="form-control rounded-3 py-2"
                            value="{{ old('halaman_saat_ini', 0) }}" placeholder="0" min="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Total Halaman</label>
                        <input type="number" name="total_halaman" class="form-control rounded-3 py-2"
                            value="{{ old('total_halaman') }}" placeholder="Contoh: 350" min="1">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Ulasan Singkat</label>
                        <textarea name="ulasan" class="form-control rounded-3" rows="4"
                            placeholder="Tuliskan pendapatmu tentang buku ini...">{{ old('ulasan') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Rating</label>
                        <div class="d-flex gap-3 align-items-center mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check form-check-inline me-0 p-0">
                                    <input class="form-check-input d-none star-input" type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                        {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label class="form-check-label" for="star{{ $i }}" style="cursor: pointer;">
                                        <i class="fa-{{ old('rating') >= $i ? 'solid text-warning' : 'regular text-muted' }} fa-star fs-3 star-icon" data-value="{{ $i }}"></i>
                                    </label>
                                </div>
                            @endfor
                        </div>
                        <small class="text-muted">Klik bintang untuk memberi nilai.</small>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <hr class="mb-4 opacity-10">
                        <a href="{{ route('buku.index') }}" class="btn btn-light me-2 px-4 rounded-pill text-muted">Batal</a>
                        <button type="submit" class="btn px-5 rounded-pill shadow-sm"
                            style="background-color: var(--amber); color: white; border: none;">
                            <i class="fas fa-save me-2"></i> Simpan Buku
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('cover_input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('cover-preview').src = e.target.result;
            document.getElementById('coverUploadArea').classList.add('has-image');
        };
        reader.readAsDataURL(file);
    });

    function removeCover() {
        document.getElementById('cover_input').value = '';
        document.getElementById('cover-preview').src = '';
        document.getElementById('coverUploadArea').classList.remove('has-image');
    }

    document.querySelectorAll('.star-icon').forEach(star => {
        star.addEventListener('click', function () {
            let val = this.getAttribute('data-value');
            let parent = this.closest('.d-flex');
            parent.querySelectorAll('.star-icon').forEach((s, idx) => {
                if (idx < val) {
                    s.classList.remove('text-muted', 'fa-regular');
                    s.classList.add('text-warning', 'fa-solid');
                    s.style.color = 'var(--amber)';
                } else {
                    s.classList.remove('text-warning', 'fa-solid');
                    s.classList.add('text-muted', 'fa-regular');
                    s.style.color = '';
                }
            });
        });
    });
</script>
@endsection