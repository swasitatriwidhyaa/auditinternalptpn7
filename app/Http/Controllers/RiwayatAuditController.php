<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Audit;

class RiwayatAuditController extends Controller
{
    public function index()
    {
        // Ambil semua user yang berperan sebagai 'auditee' (Unit)
        $units = User::where('role', 'auditee')->get(); 
        
        return view('riwayat.index', compact('units'));
    }

    public function show(Request $request, $id) // Tambahkan Request $request
    {
        // 1. Cari Unit (User)
        $unit = User::findOrFail($id);

        // 2. Siapkan Query Dasar
        $query = Audit::where('auditee_id', $id);

        // --- LOGIKA FILTER ---
        
        // Filter Tahun (jika ada input 'year')
        if ($request->filled('year')) {
            $query->whereYear('tanggal_audit', $request->year);
        }

        // Filter Bulan (jika ada input 'month')
        if ($request->filled('month')) {
            $query->whereMonth('tanggal_audit', $request->month);
        }

        // 3. Eksekusi Query (Urutkan dari yang terbaru)
        $riwayatAudits = $query->orderBy('tanggal_audit', 'desc')->get();

        return view('riwayat.show', compact('unit', 'riwayatAudits'));
    }
}