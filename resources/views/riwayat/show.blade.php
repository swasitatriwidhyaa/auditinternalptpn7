@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary">
            &larr; Kembali ke Daftar Unit
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Audit: <strong>{{ $unit->name ?? 'Unit' }}</strong></h5>
        </div>
        
        <div class="card-body">
            
            {{-- === FORM FILTER START === --}}
            <form action="{{ route('riwayat.show', $unit->id) }}" method="GET" class="mb-4 p-3 bg-light rounded border">
                <div class="row g-2 align-items-end">
                    
                    {{-- 1. Filter Standar (SUDAH DIPERBAIKI NAMANYA) --}}
                    <div class="col-md-3">
                        <label for="standard" class="form-label fw-bold small text-muted">Jenis Audit</label>
                        <select name="standard" class="form-select">
                            <option value="">-- Semua Jenis --</option>
                            
                            @foreach($standardsList as $std)
                                <option value="{{ $std->id }}" {{ request('standard') == $std->id ? 'selected' : '' }}>
                                    {{-- Mengambil nama dari Manual Mapping berdasarkan ID --}}
                                    {{ $standardNames[$std->id] ?? 'Standar #' . $std->id }}
                                </option>
                            @endforeach
                            
                        </select>
                    </div>

                    {{-- 2. Filter Bulan --}}
                    <div class="col-md-3">
                        <label for="month" class="form-label fw-bold small text-muted">Bulan</label>
                        <select name="month" class="form-select">
                            <option value="">-- Semua Bulan --</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Filter Tahun --}}
                    <div class="col-md-3">
                        <label for="year" class="form-label fw-bold small text-muted">Tahun</label>
                        <select name="year" class="form-select">
                            <option value="">-- Semua Tahun --</option>
                            @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- 4. Tombol Filter --}}
                    <div class="col-md-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success text-white">
                                <i class="bi bi-funnel-fill me-1"></i> Terapkan
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Reset --}}
                    @if(request('month') || request('year') || request('standard'))
                    <div class="col-12 mt-2 text-end">
                         <a href="{{ route('riwayat.show', $unit->id) }}" class="text-danger text-decoration-none small">
                            <i class="bi bi-x-circle"></i> Reset semua filter
                        </a>
                    </div>
                    @endif
                </div>
            </form>
            {{-- === FORM FILTER END === --}}

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Audit</th>
                            <th>Standar</th>
                            <th>Status</th>
                            <th>Lead Auditor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatAudits as $index => $audit)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if(!empty($audit->tanggal_audit))
                                    {{ \Carbon\Carbon::parse($audit->tanggal_audit)->locale('id')->isoFormat('D MMMM Y') }}
                                @else
                                    {{ $audit->created_at->format('d M Y') }}
                                @endif
                            </td>
                            <td>
                                {{-- Menampilkan Nama Standar di Tabel --}}
                                <span class="fw-bold text-dark">
                                    {{ $standardNames[$audit->standard_id] ?? 'Standar #' . $audit->standard_id }}
                                </span>
                            </td>
                            <td>
                                @if(in_array(strtolower($audit->status), ['finished', 'selesai (closed)', 'closed']))
                                    <span class="badge bg-success">Selesai (Closed)</span>
                                @elseif(in_array(strtolower($audit->status), ['ongoing', 'proses', 'process']))
                                    <span class="badge bg-warning text-dark">Proses (Ongoing)</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($audit->status) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $audit->auditor->name ?? $audit->user->name ?? 'Admin' }}
                            </td>
                            <td>
                                <a href="{{ route('audit.show', $audit->id) }}" class="btn btn-sm btn-primary">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-2"><i class="bi bi-search" style="font-size: 2rem;"></i></div>
                                <em>Tidak ada data audit yang ditemukan untuk filter ini.</em>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection