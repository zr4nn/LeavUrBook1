@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold" style="color: var(--ink);">Overview</h3>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--amber) !important; background: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold d-block mb-1">TOTAL BUKU</small>
                    <h2 class="fw-bold mb-0" style="color: var(--amber);">{{ $totalBuku }}</h2>
                </div>
                <div class="opacity-25" style="color: var(--amber);">
                    <i class="fa-solid fa-layer-group fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--amber-light) !important; background: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold d-block mb-1">SEDANG DIBACA</small>
                    <h2 class="fw-bold mb-0" style="color: var(--amber-light);">{{ $sedangDibaca }}</h2>
                </div>
                <div class="opacity-25" style="color: var(--amber-light);">
                    <i class="fa-solid fa-book-open fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--sage) !important; background: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold d-block mb-1">SELESAI</small>
                    <h2 class="fw-bold mb-0" style="color: var(--sage);">{{ $selesaiDibaca }}</h2>
                </div>
                <div class="opacity-25" style="color: var(--sage);">
                    <i class="fa-solid fa-circle-check fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--blush) !important; background: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold d-block mb-1">AVG RATING</small>
                    <h2 class="fw-bold mb-0" style="color: var(--ink);">{{ $rataRating }}</h2>
                </div>
                <div class="opacity-25" style="color: var(--blush);">
                    <i class="fa-solid fa-star fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection