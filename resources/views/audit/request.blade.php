@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- Tombol Kembali --}}
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">
        &larr; Kembali ke Dashboard
    </a>

    <div class="card border-0 shadow-sm">
        {{-- Header Biru Sesuai Screenshot --}}
        <div class="card-header bg-primary text-white fw-bold py-3">
            Form Pengajuan Audit Unit
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('audit.submit_request') }}" method="POST">
                @csrf

                {{-- 1. Input Jenis Audit --}}
                <div class="mb-4">
                    <label for="standard" class="form-label fw-bold text-dark">Jenis Audit yang Diajukan</label>
                    <select name="standard" id="standard" class="form-select bg-light" required>
                        <option value="ISO 9001:2015">ISO 9001:2015 - Sistem Manajemen Mutu</option>
                        <option value="ISO 14001:2015">ISO 14001:2015 - Sistem Manajemen Lingkungan</option>
                        <option value="ISO 45001:2018">ISO 45001:2018 - Sistem Manajemen K3</option>
                        <option value="SMAP">SMAP - Sistem Manajemen Anti Penyuapan</option>
                        <option value="Halal">Sistem Jaminan Halal</option>
                    </select>
                </div>

                {{-- 2. Input Tanggal (Sekarang Full Width karena Lokasi dihapus) --}}
                <div class="mb-4">
                    <label for="tanggal" class="form-label fw-bold text-dark">Rencana Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control bg-light" required>
                </div>

                {{-- KOLOM LOKASI SUDAH DIHAPUS --}}

                {{-- Tombol Submit --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2">
                        Kirim Pengajuan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection