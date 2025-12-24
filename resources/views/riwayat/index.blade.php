@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success"><i class="fas fa-history"></i> Arsip Riwayat Audit</h2>
    </div>

    <div class="row">
        @foreach($units as $unit)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $unit->name ?? 'Nama Unit' }}</h5>
                    <p class="card-text text-muted">
                        Klik tombol di bawah untuk melihat sejarah audit unit ini.
                    </p>
                    <a href="{{ route('riwayat.show', $unit->id) }}" class="btn btn-success w-100">
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection