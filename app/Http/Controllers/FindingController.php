<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditFinding;
use App\Models\Audit; // Jangan lupa import Model Audit
use Illuminate\Support\Facades\Storage;

class FindingController extends Controller
{
    // Fungsi Simpan Temuan (Auditor)
    public function store(Request $request)
    {
        AuditFinding::create([
            'audit_id' => $request->audit_id,
            'klausul' => $request->klausul,
            'uraian_temuan' => $request->uraian_temuan,
            'kategori' => $request->kategori,
            'status_temuan' => 'open'
        ]);
        
        // Update Status Audit jadi "Ongoing" (Karena sudah ada temuan)
        $audit = Audit::find($request->audit_id);
        if($audit->status == 'planned') {
            $audit->update(['status' => 'ongoing']);
        }

        return back()->with('success', 'Temuan ditambahkan');
    }

    // Fungsi Respon (Auditee)
    public function response(Request $request, $id)
    {
        $finding = AuditFinding::findOrFail($id);
        
        $path = $finding->bukti_perbaikan; 
        if($request->hasFile('bukti_perbaikan')){
            $path = $request->file('bukti_perbaikan')->store('bukti', 'public');
        }

        $finding->update([
            'akar_masalah' => $request->akar_masalah,
            'tindakan_koreksi' => $request->tindakan_koreksi,
            'bukti_perbaikan' => $path,
            'status_temuan' => 'responded', 
            'catatan_auditor' => null 
        ]);

        // LOGIKA BARU: BERITAHU AUDITOR BAHWA SEDANG DIKERJAKAN
        // Ubah status Audit jadi 'ongoing' agar Auditor tahu ada aktivitas
        $finding->audit->update(['status' => 'ongoing']);

        return back()->with('success', 'Respon dikirim. Status berubah menjadi Sedang Dikerjakan.');
    }

    // Fungsi Verifikasi (Auditor)
    public function verify(Request $request, $id)
    {
        $finding = AuditFinding::findOrFail($id);
        $audit = $finding->audit; // Ambil data Audit Induknya

        if ($request->aksi == 'approve') {
            // 1. Update Temuan jadi Closed
            $finding->update(['status_temuan' => 'closed']);

            // 2. CEK APAKAH SEMUA TEMUAN SUDAH CLOSED?
            // Hitung sisa temuan yang BELUM closed
            $sisa_temuan = $audit->findings()->where('status_temuan', '!=', 'closed')->count();

            if ($sisa_temuan == 0) {
                // Jika sisa 0 (artinya semua closed), maka Audit FINISHED
                $audit->update(['status' => 'finished']);
                $pesan = 'Semua temuan selesai! Status Audit berubah menjadi CLOSED/SELESAI.';
            } else {
                // Jika masih ada sisa, biarkan Ongoing
                $pesan = 'Temuan ditutup. Masih ada ' . $sisa_temuan . ' temuan yang belum selesai.';
            }

            return back()->with('success', $pesan);

        } else {
            // JIKA DITOLAK (REVISI)
            $finding->update([
                'status_temuan' => 'open', 
                'catatan_auditor' => $request->catatan_auditor
            ]);
            
            // Pastikan status Audit tetap Ongoing (Sedang Dikerjakan)
            $audit->update(['status' => 'ongoing']);

            return back()->with('error', 'Temuan DITOLAK. Unit diminta revisi.');
        }
    }

    // Fungsi Reopen
    public function reopen($id)
    {
        $finding = AuditFinding::findOrFail($id);
        $finding->update(['status_temuan' => 'open']);
        
        // Kembalikan status Audit ke Ongoing jika dibuka lagi
        $finding->audit->update(['status' => 'ongoing']);

        return back()->with('success', 'Temuan DIBUKA KEMBALI.');
    }
}