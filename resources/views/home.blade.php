@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- HEADER: Sapaan & Tanggal --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Dashboard Monitoring</h4>
            <p class="text-secondary mb-0">
                Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>! 
                <br><small class="text-muted">Pantau perkembangan audit unit terkini di sini.</small>
            </p>
        </div>
        <div class="d-none d-md-block">
            <div class="card border-0 bg-light px-3 py-2">
                <span class="fw-bold text-success">
                    <i class="bi bi-calendar-check me-2"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: GRAFIK RINGKAS --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <h6 class="fw-bold text-muted mb-4 text-start">PROGRES KESELURUHAN</h6>
                    
                    {{-- Grafik Donut --}}
                    <div class="position-relative d-inline-block mb-3" style="width: 200px; height: 200px;">
                        <svg width="100%" height="100%" viewBox="0 0 42 42" class="donut">
                            <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                            <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#f8f9fa" stroke-width="4"></circle>
                            
                            <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" 
                                fill="transparent" stroke="#198754" stroke-width="4" 
                                stroke-dasharray="{{ ($totalAudit > 0) ? ($totalSelesai / $totalAudit) * 100 : 0 }} {{ ($totalAudit > 0) ? 100 - (($totalSelesai / $totalAudit) * 100) : 100 }}" stroke-dashoffset="25"></circle>
                            
                            <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" 
                                fill="transparent" stroke="#ffc107" stroke-width="4" 
                                stroke-dasharray="{{ ($totalAudit > 0) ? ($totalProses / $totalAudit) * 100 : 0 }} {{ ($totalAudit > 0) ? 100 - (($totalProses / $totalAudit) * 100) : 100 }}" stroke-dashoffset="{{ ($totalAudit > 0) ? 100 - (($totalSelesai / $totalAudit) * 100) + 25 : 0 }}"></circle>
                        </svg>
                        
                        {{-- Angka Tengah --}}
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h1 class="display-4 fw-bold text-dark mb-0">{{ $totalAudit }}</h1>
                            <span class="badge bg-light text-secondary border">TOTAL AUDIT</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="row text-center mt-2 small">
                        <div class="col-4">
                            <span class="d-block fw-bold text-success">{{ $totalSelesai }}</span>
                            <span class="text-muted" style="font-size: 11px;">Selesai</span>
                        </div>
                        <div class="col-4 border-start border-end">
                            <span class="d-block fw-bold text-warning">{{ $totalProses }}</span>
                            <span class="text-muted" style="font-size: 11px;">Proses</span>
                        </div>
                        <div class="col-4">
                            <span class="d-block fw-bold text-secondary">{{ $totalOpen }}</span>
                            <span class="text-muted" style="font-size: 11px;">Open</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: KARTU STATISTIK, QUICK ACTION & DEADLINE --}}
        <div class="col-lg-8">
            
            {{-- 1. KARTU STATISTIK --}}
            <div class="row g-3 mb-4">
                {{-- Card Total --}}
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm overflow-hidden h-100 bg-primary bg-gradient text-white">
                        <div class="card-body p-3 position-relative">
                            <h2 class="fw-bold mb-0">{{ $totalAudit }}</h2>
                            <small class="text-white-50">Semua Data</small>
                            <i class="bi bi-folder2-open position-absolute top-50 end-0 translate-middle-y me-3" style="font-size: 3rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
                {{-- Card Open --}}
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body p-3 position-relative" style="border-left: 5px solid #6c757d;">
                            <h2 class="fw-bold text-secondary mb-0">{{ $totalOpen }}</h2>
                            <small class="text-muted">Draft/Open</small>
                            <i class="bi bi-file-earmark position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="font-size: 3rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
                {{-- Card Ongoing --}}
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body p-3 position-relative" style="border-left: 5px solid #ffc107;">
                            <h2 class="fw-bold text-warning mb-0">{{ $totalProses }}</h2>
                            <small class="text-muted">Sedang Proses</small>
                            <i class="bi bi-hourglass-split position-absolute top-50 end-0 translate-middle-y me-3 text-warning" style="font-size: 3rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
                {{-- Card Selesai --}}
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body p-3 position-relative" style="border-left: 5px solid #198754;">
                            <h2 class="fw-bold text-success mb-0">{{ $totalSelesai }}</h2>
                            <small class="text-muted">Tuntas/Closed</small>
                            <i class="bi bi-check-circle position-absolute top-50 end-0 translate-middle-y me-3 text-success" style="font-size: 3rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. QUICK ACTION BAR (MENGISI KOTAK MERAH) --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 text-success">
                            <i class="bi bi-lightning-charge-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Aksi Cepat</h6>
                            <small class="text-muted">Kelola data audit dengan efisien.</small>
                        </div>
                    </div>
                    
                    {{-- Tombol Aksi Sesuai Role --}}
                    <div>
                        @if(Auth::user()->role == 'auditor')
                            <a href="{{ route('audit.create') }}" class="btn btn-success fw-bold px-4">
                                <i class="bi bi-plus-lg me-2"></i> Jadwal Audit Baru
                            </a>
                        @elseif(Auth::user()->role == 'auditee')
                            <a href="{{ route('audit.request.form') }}" class="btn btn-success fw-bold px-4">
                                <i class="bi bi-plus-lg me-2"></i> Ajukan Audit
                            </a>
                        @else
                            {{-- Fallback untuk admin/user lain --}}
                            <a href="#" class="btn btn-outline-secondary fw-bold px-4">
                                <i class="bi bi-arrow-right-circle me-2"></i> Lihat Laporan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. DEADLINE SECTION --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-alarm text-danger me-2"></i> Perhatian Khusus (Deadline Terdekat)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingDeadlines as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 text-danger rounded p-2 me-3 text-center" style="width: 50px;">
                                        <small class="d-block fw-bold">{{ \Carbon\Carbon::parse($item->deadline)->format('d') }}</small>
                                        <small class="d-block" style="font-size: 10px;">{{ \Carbon\Carbon::parse($item->deadline)->format('M') }}</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $item->auditee->name ?? 'Unit Tidak Dikenal' }}</h6>
                                        <small class="text-muted">Standard: {{ $item->standard_id ?? 'ISO 9001' }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark">{{ \Carbon\Carbon::parse($item->deadline)->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light">
                                <i class="bi bi-shield-check text-success display-6 mb-2"></i>
                                <p class="text-muted mb-0 small">Tidak ada audit yang mendekati deadline (H-7). Aman!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- BARIS BAWAH: TABEL DAFTAR AUDIT --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i> 5 Aktifitas Audit Terbaru</h6>
            @if(Auth::user()->role == 'auditor')
                <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Riwayat</a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Unit / Auditee</th>
                        <th>Tanggal Audit</th>
                        <th>Standar</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestAudits as $audit)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light rounded-circle text-center me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-building text-secondary"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">{{ $audit->auditee->name ?? '-' }}</span>
                                    <small class="text-muted" style="font-size: 11px;">Auditor: {{ $audit->auditor->name ?? 'Belum ada' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(!empty($audit->tanggal_audit))
                                {{ \Carbon\Carbon::parse($audit->tanggal_audit)->format('d M Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $audit->standard_id ?? 'ISO 9001' }}</td>
                        <td>
                            @if(in_array(strtolower($audit->status), ['finished', 'selesai (closed)', 'closed']))
                                <span class="badge bg-success bg-opacity-10 text-success px-3">Selesai</span>
                            @elseif(in_array(strtolower($audit->status), ['ongoing', 'proses', 'process']))
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3">Proses</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3">Open</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('audit.show', $audit->id) }}" class="btn btn-sm btn-light text-primary border">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data audit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection