<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $limit = $request->query('limit', 12);
        $divisi_id = $request->query('divisi_id');

        $query = Pekerjaan::with(['getSubPekerjaan', 'getStatusPekerjaan', 'getUser'])
            ->where('status_pekerjaan_id', '!=', 5);

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_mulai', [$start_date, $end_date]);
        }

        if ($divisi_id) {
            $query->whereHas('getUser', function ($q) use ($divisi_id) {
                $q->where('divisi_id', $divisi_id);
            });
        }

        $pekerjaan = $query->limit($limit)->get();
        $divisi = Divisi::all();

        if ($request->ajax()) {
            return view('partials.papan-kerja', compact('pekerjaan'))->render();
        }

        return view('papan-kerja', compact('pekerjaan', 'divisi', 'limit'));
    }
}
