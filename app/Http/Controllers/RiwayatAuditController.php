<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Audit;
use App\Models\AuditStandard;

class RiwayatAuditController extends Controller
{
    public function index()
    {
        $units = User::where('role', 'auditee')->get(); 
        return view('riwayat.index', compact('units'));
    }

    public function show(Request $request, $id)
    {
        // 1. Cari Unit
        $unit = User::findOrFail($id);

        // 2. Ambil Daftar Standar dari Database
        $standardsList = AuditStandard::all(); 

        // --- SOLUSI MANUAL MAPPING (Sesuai Request Kamu) ---
        // Kita petakan ID ke Nama Standar secara manual
        $standardNames = [
            1 => 'ISO 9001:2015',
            2 => 'ISO 14001:2015',
            3 => 'SMAP (ISO 37001)',
            4 => 'SNI',
            5 => 'IMS',
            6 => 'SMK3',
            7 => 'Halal'
        ];

        // 3. Siapkan Query Dasar
        $query = Audit::where('auditee_id', $id);

        // --- LOGIKA FILTER ---
        if ($request->filled('standard')) {
            $query->where('standard_id', $request->standard);
        }
        if ($request->filled('year')) {
            $query->whereYear('tanggal_audit', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('tanggal_audit', $request->month);
        }

        // 4. Eksekusi Query
        $riwayatAudits = $query->orderBy('tanggal_audit', 'desc')->get();

        // Kirim $standardNames juga ke view
        return view('riwayat.show', compact('unit', 'riwayatAudits', 'standardsList', 'standardNames'));
    }
}