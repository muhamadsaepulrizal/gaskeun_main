<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer.pangkalanProfile', 'causer.profilAgen'])->latest();

        // Filter: Tanggal Awal & Akhir
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Filter: Kata Kunci (Aksi / Event / Modul)
        if ($request->filled('description')) {
            $keyword = $request->description;
            $keywordAlias = $keyword;

            // Handle khusus untuk kata pencarian "login" dan "logout" karena di database formatnya "logged in"
            if (strtolower($keyword) === 'login') $keywordAlias = 'logged in';
            if (strtolower($keyword) === 'logout') $keywordAlias = 'logged out';

            $query->where(function($q) use ($keyword, $keywordAlias) {
                $q->where('description', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keywordAlias}%")
                  ->orWhere('event', 'like', "%{$keyword}%")
                  ->orWhere('subject_type', 'like', "%{$keyword}%")
                  ->orWhere('log_name', 'like', "%{$keyword}%");
            });
        }

        // Filter: User (Causer)
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        // Ekspor ke PDF
        if ($request->has('export_pdf')) {
            $logs = $query->get();
            $pdf = Pdf::loadView('superadmin.logs.pdf', compact('logs'))
                ->setPaper('a4', 'landscape');
                
            return $pdf->download('activity_log_' . now()->format('Ymd_His') . '.pdf');
        }

        $logs = $query->paginate(20)->withQueryString();
        
        $users = \App\Models\User::with(['pangkalanProfile', 'profilAgen'])->orderBy('name')->get();

        return view('superadmin.logs.index', compact('logs', 'users'));
    }
}
