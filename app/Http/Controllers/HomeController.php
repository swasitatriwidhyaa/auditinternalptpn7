<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit; // Pastikan Model Audit di-import
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Hitung Statistik
        $totalAudit = Audit::count();
        $totalOpen = Audit::whereIn('status', ['open', 'Open'])->count();
        $totalProses = Audit::whereIn('status', ['ongoing', 'proses', 'Proses', 'Process'])->count();
        $totalSelesai = Audit::whereIn('status', ['finished', 'selesai (closed)', 'Selesai (Closed)', 'closed'])->count();

        // 2. Audit Deadline Terdekat (Limit 3)
        $upcomingDeadlines = Audit::whereNotIn('status', ['finished', 'selesai (closed)', 'closed'])
                                  ->whereDate('deadline', '>=', now())
                                  ->orderBy('deadline', 'asc')
                                  ->take(3)
                                  ->get();

        // 3. (BARU) Daftar 5 Audit Terbaru untuk Tabel Bawah
        $latestAudits = Audit::with(['auditor', 'auditee']) // Pastikan relasi diload
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();

        return view('home', compact(
            'totalAudit', 'totalOpen', 'totalProses', 'totalSelesai',
            'upcomingDeadlines', 'latestAudits'
        ));
    }
}