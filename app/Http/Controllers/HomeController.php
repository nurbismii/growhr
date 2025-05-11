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
        $divisi_id = $request->query('divisi_id');

        $query = Pekerjaan::with(['getSubPekerjaan.getKategoriPekerjaan', 'getStatusPekerjaan', 'getUser'])
            ->where('status_pekerjaan_id', '!=', 5)
            ->orWhere('status_pekerjaan_id', '!=', 6);

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
        $user = User::all();

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
            'pekerjaan.getUser'
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

            // Filter berdasarkan prioritas pekerjaan
            if ($request->filled('prioritas')) {
                $riwayatQuery->whereHas('prioritas_id', function ($query) use ($request) {
                    $query->whereIn('prioritas_id', $request->prioritas);
                });
            }

            // Filter berdasarkan PIC (user_id pada pekerjaan)
            if ($request->filled('pic')) {
                $pic = is_array($request->pic) ? $request->pic : [$request->pic];

                $riwayatQuery->whereHas('pekerjaan', function ($query) use ($pic) {
                    $query->whereIn('user_id', $pic);
                });
            }

            // Filter berdasarkan tanggal pembaruan
            if ($request->filled('tanggal')) {
                $dates = explode(" - ", $request->tanggal);
                if (count($dates) === 2) {
                    $startDate = date('Y-m-d', strtotime(trim($dates[0])));
                    $endDate = date('Y-m-d', strtotime(trim($dates[1])));
                    $riwayatQuery->whereBetween('pembaruan', [$startDate, $endDate]);
                }
            }

            return response()->json([
                'riwayat' => $riwayatQuery->get(),
                'riwayat_chart' => $riwayatQuery->limit(10)->get(),
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

    public function autosave(Request $request)
    {
        Storage::put('informasi-penting.txt', $request->input('informasi'));
        return response()->json(['status' => 'ok']);
    }
}
