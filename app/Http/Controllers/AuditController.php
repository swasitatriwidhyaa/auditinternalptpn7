<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\AuditStandard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function show($id)
    {
        $audit = Audit::with(['findings', 'standard', 'auditee', 'auditor'])->findOrFail($id);
        return view('audit.show', compact('audit'));
    }

    public function create()
    {
        $standards = AuditStandard::all();
        $auditees = User::where('role', 'auditee')->get();
        return view('audit.create', compact('standards', 'auditees'));
    }

    public function store(Request $request)
    {
        // VALIDASI: Deadline wajib diisi & tidak boleh sebelum tanggal audit
        $request->validate([
            'standard_id' => 'required', 
            'auditee_id' => 'required',
            'tanggal_audit' => 'required|date', 
            'deadline' => 'required|date|after_or_equal:tanggal_audit', // <--- DIGANTI
        ]);

        Audit::create([
            'standard_id' => $request->standard_id,
            'auditor_id' => Auth::id(),
            'auditee_id' => $request->auditee_id,
            'tanggal_audit' => $request->tanggal_audit,
            'deadline' => $request->deadline, // <--- DIGANTI (Masuk ke kolom deadline)
            'status' => 'planned'
        ]);

        return redirect()->route('home')->with('success', 'Jadwal Audit & Deadline Berhasil Dibuat!');
    }

    // --- FITUR BARU: REQUEST AUDIT ---

    public function requestForm()
    {
        $standards = AuditStandard::all();
        return view('audit.request', compact('standards'));
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'standard_id' => 'required',
            'tanggal_audit' => 'required|date',
            // Lokasi dihapus, request audit biasanya belum ada deadline pasti (bisa null atau diset sama)
            // Disini kita set deadline sementara sama dengan tanggal audit (nanti diedit auditor)
        ]);

        $auditor = User::where('role', 'auditor')->first(); 

        Audit::create([
            'standard_id' => $request->standard_id,
            'auditor_id' => $auditor->id,
            'auditee_id' => Auth::id(),
            'tanggal_audit' => $request->tanggal_audit,
            'deadline' => $request->tanggal_audit, // Default deadline = hari H (nanti diubah auditor)
            'status' => 'requested'
        ]);

        return redirect()->route('home')->with('success', 'Pengajuan Audit berhasil dikirim ke SPI!');
    }

    public function approveAudit($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->update(['status' => 'planned']);
        return back()->with('success', 'Ajuan Audit Disetujui!');
    }
}