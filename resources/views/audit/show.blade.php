@extends('layouts.app')

@section('content')
<style>
    /* Styling Tabel Excel-like */
    .table-audit thead tr:first-child th { text-align: center; text-transform: uppercase; letter-spacing: 1px; }
    .bg-red-soft { background-color: #fff5f5; border: 2px solid #dc3545 !important; }
    .bg-green-soft { background-color: #f0fdf4; border: 2px solid #198754 !important; }
    .border-red { border-left: 2px solid #dc3545; border-right: 2px solid #dc3545; }
    .border-green { border-left: 2px solid #198754; border-right: 2px solid #198754; }
    textarea.form-control { resize: vertical; min-height: 100px; font-size: 0.9rem; }
    
    /* ANIMASI KEDIP MERAH (Supaya Alasan Penolakan Terlihat Jelas) */
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .alert-revisi {
        animation: pulse-red 2s infinite;
        border-left: 5px solid #dc3545;
        background-color: #fff8f8;
        color: #dc3545;
    }
</style>

<div class="container-fluid px-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">Audit Checklist & Tindak Lanjut</h3>
            <span class="text-muted">{{ $audit->standard->kode }} - {{ $audit->standard->nama }}</span>
        </div>
        <div class="text-end">
            <button onclick="window.print()" class="btn btn-secondary btn-sm no-print"><i class="bi bi-printer"></i> Cetak PDF</button>
            <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm no-print">Kembali</a>
        </div>
    </div>

    {{-- INFO KARTU --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row text-sm">
                <div class="col-md-2 border-end"><strong>Auditee/Unit:</strong><br>{{ $audit->auditee->unit_kerja }}</div>
                <div class="col-md-2 border-end"><strong>Auditor:</strong><br>{{ $audit->auditor->name }}</div>
                <div class="col-md-2 border-end"><strong>Tanggal:</strong><br>{{ date('d M Y', strtotime($audit->tanggal_audit)) }}</div>
                <div class="col-md-2"><strong>Deadline:</strong><br><span class="text-danger fw-bold">{{ date('d M Y', strtotime($audit->deadline)) }}</span></div>
            </div>
        </div>
    </div>

    {{-- TABEL UTAMA --}}
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-audit align-middle mb-0">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-red-soft text-danger py-3">
                                <i class="bi bi-person-fill-exclamation"></i> BAGIAN 1: TEMUAN AUDITOR (PLOR)
                            </th>
                            <th colspan="3" class="bg-green-soft text-success py-3">
                                <i class="bi bi-pencil-square"></i> BAGIAN 2: RESPON UNIT (AUDITEE)
                            </th>
                        </tr>
                        <tr class="bg-light text-center small fw-bold text-secondary">
                            <th width="5%" class="border-red">No</th>
                            <th width="10%" class="border-red">Kategori / Klausul</th>
                            <th width="25%" class="border-red">Uraian (Problem)</th>
                            <th width="10%" class="border-red">Deadline</th>
                            <th width="20%" class="border-green">Akar Masalah</th>
                            <th width="20%" class="border-green">Tindakan & Bukti</th>
                            <th width="10%" class="border-green">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audit->findings as $index => $finding)
                        <tr>
                            {{-- KOLOM AUDITOR --}}
                            <td class="text-center border-red bg-white">{{ $index + 1 }}</td>
                            <td class="border-red bg-white">
                                <span class="badge bg-secondary mb-1">{{ ucfirst($finding->kategori) }}</span><br>
                                <strong>Kl:</strong> {{ $finding->klausul }}
                            </td>
                            <td class="border-red bg-white">
                                <p class="mb-0 text-dark">{{ $finding->uraian_temuan }}</p>
                            </td>
                            <td class="text-center border-red bg-white text-danger fw-bold">
                                {{ date('d M Y', strtotime($audit->deadline)) }}
                            </td>

                            {{-- KOLOM AUDITEE (FORM / TEXT) --}}
                            
                            {{-- FORM HANYA MUNCUL JIKA USER=AUDITEE DAN STATUS=OPEN (Baru atau Ditolak) --}}
                            @if(Auth::user()->role == 'auditee' && $finding->status_temuan == 'open')
                                <form action="{{ route('finding.response', $finding->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    {{-- INPUT AKAR MASALAH --}}
                                    <td class="border-green bg-green-soft align-top">
                                        
                                        {{-- ⚠️ INI BAGIAN ALERT ALASAN PENOLAKAN ⚠️ --}}
                                        @if($finding->catatan_auditor)
                                            <div class="alert alert-danger alert-revisi small mb-3 p-2 shadow-sm">
                                                <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill"></i> DITOLAK / REVISI:</strong>
                                                <i class="bi bi-chat-quote-fill me-1"></i> "{{ $finding->catatan_auditor }}"
                                            </div>
                                        @endif
                                        {{-- ⚠️ END ALERT ⚠️ --}}

                                        <label class="small fw-bold mb-1 text-success">Akar Masalah</label>
                                        <textarea name="akar_masalah" class="form-control form-control-sm border-success" placeholder="Jelaskan penyebab masalah..." required>{{ $finding->akar_masalah }}</textarea>
                                    </td>

                                    {{-- INPUT TINDAKAN --}}
                                    <td class="border-green bg-green-soft align-top">
                                        <label class="small fw-bold mb-1 text-success">Tindakan Perbaikan</label>
                                        <textarea name="tindakan_koreksi" class="form-control form-control-sm border-success mb-2" placeholder="Jelaskan perbaikan..." required>{{ $finding->tindakan_koreksi }}</textarea>
                                        
                                        <label class="small fw-bold text-muted">Upload Bukti Baru:</label>
                                        <input type="file" name="bukti_perbaikan" class="form-control form-control-sm">
                                        @if($finding->bukti_perbaikan)
                                            <div class="mt-2 small">
                                                <a href="{{ asset('storage/'.$finding->bukti_perbaikan) }}" target="_blank" class="text-danger text-decoration-none">
                                                    <i class="bi bi-file-earmark-image"></i> Lihat Bukti Lama (Yg Ditolak)
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- TOMBOL SIMPAN --}}
                                    <td class="text-center border-green bg-green-soft align-middle">
                                        <button type="submit" class="btn btn-success btn-sm w-100 mb-2 shadow-sm"><i class="bi bi-save"></i> Kirim Perbaikan</button>
                                        <span class="badge bg-warning text-dark">Status: Revisi/Open</span>
                                    </td>
                                </form>

                            @else
                                {{-- JIKA status Responded, Closed, atau User=Auditor --}}
                                <td class="border-green bg-white">
                                    {{ $finding->akar_masalah ?? '-' }}
                                </td>
                                <td class="border-green bg-white">
                                    <p class="mb-1">{{ $finding->tindakan_koreksi ?? '-' }}</p>
                                    @if($finding->bukti_perbaikan)
                                        <a href="{{ asset('storage/'.$finding->bukti_perbaikan) }}" target="_blank" class="btn btn-outline-primary btn-sm" style="font-size:0.7rem;">
                                            <i class="bi bi-paperclip"></i> Lihat Bukti
                                        </a>
                                    @endif
                                </td>
                                
                                {{-- KOLOM AKSI / STATUS --}}
                                <td class="text-center border-green bg-white align-top">
                                    {{-- 1. CLOSED --}}
                                    @if($finding->status_temuan == 'closed')
                                        <span class="badge bg-success w-100 py-2 mb-2">CLOSED</span>
                                        @if(Auth::user()->role == 'auditor')
                                            <form action="{{ route('finding.reopen', $finding->id) }}" method="POST" onsubmit="return confirm('Buka kembali temuan ini?');">
                                                @csrf
                                                <button class="btn btn-outline-secondary btn-sm w-100" style="font-size:0.7rem;"><i class="bi bi-unlock"></i> Buka Lagi</button>
                                            </form>
                                        @endif
                                        @if($finding->catatan_auditor && str_contains($finding->catatan_auditor, 'Sistem'))
                                            <div class="small text-muted fst-italic mt-1">{{ $finding->catatan_auditor }}</div>
                                        @endif

                                    {{-- 2. RESPONDED (Auditor Action) --}}
                                    @elseif($finding->status_temuan == 'responded' && Auth::user()->role == 'auditor')
                                        <div class="d-grid gap-2">
                                            <form action="{{ route('finding.verify', $finding->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="aksi" value="approve">
                                                <button class="btn btn-primary btn-sm w-100" onclick="return confirm('Terima & Close?')">✔ Terima</button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#rev{{$finding->id}}">✖ Tolak</button>
                                        </div>
                                        <div class="collapse mt-2" id="rev{{$finding->id}}">
                                            <form action="{{ route('finding.verify', $finding->id) }}" method="POST" class="border p-2 bg-light shadow-sm rounded">
                                                @csrf
                                                <input type="hidden" name="aksi" value="reject">
                                                <label class="small fw-bold text-danger mb-1">Alasan Penolakan:</label>
                                                <textarea name="catatan_auditor" class="form-control form-control-sm mb-1" rows="2" placeholder="Tulis alasan..." required></textarea>
                                                <button class="btn btn-dark btn-sm w-100" style="font-size:0.7rem">Kirim Revisi</button>
                                            </form>
                                        </div>

                                    {{-- 3. RESPONDED (Unit View) --}}
                                    @elseif($finding->status_temuan == 'responded')
                                        <span class="badge bg-info text-dark w-100 py-2 mb-2">Menunggu Verifikasi</span>
                                        <small class="text-muted d-block" style="font-size: 0.7rem">(Data Terkunci)</small>

                                    {{-- 4. OPEN (View Only for Auditor / Others) --}}
                                    @else
                                        <span class="badge bg-secondary w-100">Open</span>
                                        @if($finding->catatan_auditor)
                                            <div class="alert alert-danger p-1 mt-2 small"><strong>Revisi:</strong><br>{{ $finding->catatan_auditor }}</div>
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5">Belum ada temuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- INPUT FINDING (AUDITOR) --}}
    @if(Auth::user()->role == 'auditor' && $audit->status != 'finished')
        <div class="card border-danger mt-5 shadow-sm no-print">
            <div class="card-header bg-danger text-white fw-bold"><i class="bi bi-plus-circle"></i> Input Temuan Baru</div>
            <div class="card-body">
                <form action="{{ route('finding.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="audit_id" value="{{ $audit->id }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="small fw-bold">Klausul</label>
                            <input type="text" name="klausul" class="form-control" placeholder="Cth: 9.2" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="observasi">Observasi</option>
                                <option value="minor">Minor</option>
                                <option value="major">Major</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="small fw-bold">Uraian Temuan</label>
                            <textarea name="uraian_temuan" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-danger px-4">Simpan Temuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection