<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Prioritas;
use App\Models\RiwayatPembaruanStatusPekerjaan;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function index(Request $request)
    {
        return view('home');
    }

    public function papanKerja(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $limit = $request->query('limit', 12);
        $status = $request->query('status');
        $divisi_id = $request->query('divisi_id');

        if ($status == "selesai") {
            $query = Pekerjaan::with(['getSubPekerjaan.getKategoriPekerjaan', 'getStatusPekerjaan', 'getUser'])
                ->whereIn('status_pekerjaan_id', [5, 7]);
        } else {
            $query = Pekerjaan::with(['getSubPekerjaan.getKategoriPekerjaan', 'getStatusPekerjaan', 'getUser'])
                ->whereNotIn('status_pekerjaan_id', [5, 7]);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_mulai', [$start_date, $end_date]);
        }

        if ($divisi_id) {
            $query->whereHas('getUser', function ($q) use ($divisi_id) {
                $q->where('divisi_id', $divisi_id);
            });
        }

        $pekerjaan = $query->orderBy('created_at', 'desc')->limit($limit)->get();
        $divisi = Divisi::all();

        if ($request->ajax()) {
            return view('partials.papan-kerja', compact('pekerjaan'))->render();
        }

        return view('papan-kerja', compact('pekerjaan', 'divisi', 'limit'));
    }

    public function kalender(Request $request)
    {
        $selesai = 5;

        function stringToColor($string)
        {
            $code = dechex(crc32($string)); // hash string
            return '#' . substr($code, 0, 6); // ambil 6 karakter awal
        }

        if ($request->ajax()) {
            $data = Pekerjaan::with('getUser')
                ->where('status_pekerjaan_id', '!=', $selesai)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->deskripsi_pekerjaan . ' | ' . $item->getUser->name, // FullCalendar butuh "title"
                        'start' => $item->tanggal_mulai,
                        'end' => $item->tanggal_selesai,
                        'color' => stringToColor($item->deskripsi_pekerjaan), //
                    ];
                });

            return response()->json($data);
        }

        return view('kalender-kerja');
    }

    public function tugas(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        // Data pendukung dropdown filter
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();
        $pekerjaan = Pekerjaan::all();
        $user_chart = User::all();

        if ((Auth::user()->role == 'ADMIN') || (Auth::user()->role == 'ASMEN')) {
            $user = User::where('nik', '!=', null)->orderBy('name', 'asc')->get();
        } else {
            $user = User::where('nik', '!=', null)->where('id', Auth::user()->id)->orderBy('name', 'asc')->get();
        }

        // Query dasar
        $riwayatQuery = RiwayatPembaruanStatusPekerjaan::with([
            'pekerjaan' => function ($query) {
                $query->select('id', 'deskripsi_pekerjaan', 'tanggal_mulai', 'status_pekerjaan_id', 'prioritas_id', 'user_id')
                    ->whereNotNull('deskripsi_pekerjaan')
                    ->whereNotNull('status_pekerjaan_id')
                    ->whereHas('getStatusPekerjaan')
                    ->whereHas('getPrioritas')
                    ->whereHas('getUser');
            },
            'pekerjaan.getStatusPekerjaan',
            'pekerjaan.getPrioritas',
            'pekerjaan.getUser',
            'subPekerjaan' => function ($query) {
                $query->select('id', 'sub_deskripsi_pekerjaan');
            }
        ])
            ->whereHas('pekerjaan', function ($query) {
                $query->whereNotNull('id')
                    ->whereNotNull('deskripsi_pekerjaan')
                    ->whereNotNull('status_pekerjaan_id')
                    ->whereNotNull('user_id');
            });

        // Filter untuk request AJAX
        if ($request->ajax()) {
            // Filter berdasarkan pekerjaan
            if ($request->filled('pekerjaan_id')) {
                $riwayatQuery->whereIn('pekerjaan_id', $request->pekerjaan_id);
            }

            // Filter berdasarkan PIC (user_id pada pekerjaan)
            if ($request->filled('pic')) {
                $pic = is_array($request->pic) ? $request->pic : [$request->pic];

                $riwayatQuery->whereHas('pekerjaan', function ($query) use ($pic) {
                    $query->whereIn('user_id', $pic);
                });
            }

            // Tanpa filter → limit 10
            if (!$request->hasAny(['pekerjaan_id', 'pic'])) {
                $riwayatQuery->orderBy('pembaruan', 'desc')->limit($request->get('limit', 10));
            }

            // Jalankan query 1x
            $riwayat = $riwayatQuery->get();

            return response()->json([
                'riwayat' => $riwayat,
                'riwayat_chart' => $riwayat,  // chart pakai hasil yang sama
                'status_pekerjaan' => $status_pekerjaan
            ]);
        }

        // Jika bukan AJAX, ambil semua riwayat
        $riwayat = $riwayatQuery->get();
        $riwayat_chart = $riwayatQuery->limit(10)->get();

        return view('tugas', compact(
            'riwayat',
            'riwayat_chart',
            'pekerjaan',
            'user',
            'prioritas',
            'status_pekerjaan',
            'user_chart'
        ));
    }

    public function audit()
    {
        $audits = \OwenIt\Auditing\Models\Audit::with([
            'user',
            'auditable' // jika auditable adalah Pekerjaan
        ])->get();

        return view('audit', compact('audits'));
    }

    public function autosave(Request $request)
    {
        Storage::put('informasi-penting.txt', $request->input('informasi'));
        return response()->json(['status' => 'ok']);
    }
}
