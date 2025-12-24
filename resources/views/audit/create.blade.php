@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">Buat Jadwal Audit Baru</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('audit.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-bold">Pilih Standar Audit</label>
                            <select name="standard_id" class="form-select" required>
                                <option value="">-- Pilih Standar (ISO, SMAP, dll) --</option>
                                @foreach($standards as $std)
                                    <option value="{{ $std->id }}">{{ $std->kode }} - {{ $std->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Pilih Auditee (Unit yang diperiksa)</label>
                            <select name="auditee_id" class="form-select" required>
                                <option value="">-- Pilih Penanggung Jawab Unit --</option>
                                @foreach($auditees as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Tanggal Audit Dimulai</label>
                                <input type="date" name="tanggal_audit" class="form-control" required>
                            </div>
                            
                            {{-- BAGIAN INI DIGANTI: DARI LOKASI JADI DEADLINE --}}
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold text-danger">Batas Waktu (Deadline)</label>
                                <input type="date" name="deadline" class="form-control" required>
                                <small class="text-muted">Batas akhir penyelesaian tindak lanjut.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection