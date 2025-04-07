<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Pengaduan;
use App\Models\Prioritas;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanhasilController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        $sifat_pekerjaan = SifatPekerjaan::all();
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan', 'asc')->get();
        $prioritas = Prioritas::orderBy('prioritas', 'asc')->get();
        $pekerjaan = Pekerjaan::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->get();
        $user = User::where('nik', '!=', null)->get();

        $hasil = Hasil::with('pekerjaan', 'pic', 'prioritas')->where('user_id', Auth::user()->id);

        $modal_hasil = Hasil::with('pekerjaan', 'pic', 'prioritas')->where('user_id', Auth::user()->id)->get();
        $modal_pekerjaan = Pekerjaan::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->get();

        if ($request->ajax()) {
            if ($request->has('pekerjaan_id')) {
                $hasil->where('pekerjaan_id', $request->pekerjaan_id);
            }
            if ($request->has('status_laporan')) {
                $hasil->where('status_laporan', $request->status_laporan);
            }
            if ($request->has('pic')) {
                $hasil->where('user_id', $request->pic);
            }
            if ($request->has('tanggal')) {
                $dates = explode(" - ", $request->tanggal);

                if (count($dates) == 2) {
                    $startDate = date('Y-m-d', strtotime(trim($dates[0]))); // Konversi ke format Y-m-d
                    $endDate = date('Y-m-d', strtotime(trim($dates[1])));

                    $hasil->whereBetween('created_at', array($startDate, $endDate));
                }
            }

            return response()->json([
                'hasil' => $hasil->get(),
                'status_laporan' => [
                    'diajukan',
                    'disetujui',
                    'revisi',
                ],
                'pekerjaan'
            ]);
        }

        $hasil = $hasil->get();

        return view('laporan-hasil.index', compact(
            'hasil',
            'sifat_pekerjaan',
            'kategori_pekerjaan',
            'user',
            'prioritas',
            'pekerjaan',
            'modal_pekerjaan',
            'modal_hasil',
        ))->with('no');
    }

    public function create()
    {
        $pekerjaan = Pekerjaan::where('user_id', Auth::user()->id)->get();

        return view('laporan-hasil.create', compact('pekerjaan'));
    }

    public function store(Request $request)
    {
        $doc_laporan = null;

        if ($request->hasFile('doc_laporan')) {
            // Tentukan path penyimpanan relatif dalam storage/app/public
            $path = 'Laporan Hasil/' . Auth::user()->name . '/' . date('dmY');

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory($path);

            // Simpan file dengan nama asli ke dalam path yang benar
            $doc_laporan = $request->file('doc_laporan')->storeAs(
                $path, // Folder dalam `storage/app/public/`
                $request->file('doc_laporan')->getClientOriginalName(),
                'public' // Gunakan disk 'public'
            );
        }

        Hasil::create([
            'user_id' => Auth::user()->id,
            'pekerjaan_id' => $request->pekerjaan_id,
            'status_laporan' => $request->status_laporan,
            'keterangan' => $request->keterangan,
            'doc_laporan' => $doc_laporan
        ]);

        Alert::success('Berhasil', 'Laporan hasil berhasil ditambahkan');
        return redirect('laporan-hasil');
    }

    public function update(Request $request, $id)
    {
        $hasil = Hasil::findOrFail($id);

        if ($request->hasFile('doc_laporan')) {
            // Hapus file lama jika ada
            if ($hasil->doc_laporan) {
                Storage::disk('public')->delete($hasil->doc_laporan);
            }

            $path = 'Laporan Hasil/' . Auth::user()->name . '/' . date('dmY');
            Storage::disk('public')->makeDirectory($path);
            $doc_laporan = $request->file('doc_laporan')->storeAs(
                $path,
                $request->file('doc_laporan')->getClientOriginalName(),
                'public'
            );
        }

        $hasil->update([
            'status_laporan' => $request->status_laporan,
            'keterangan' => $request->keterangan,
            'doc_laporan' => $doc_laporan ?? $hasil->doc_laporan
        ]);

        Alert::success('Berhasil', 'Laporan hasil berhasil diperbarui');
        return back();
    }

    public function updateStatusLaporan(Request $request, $id)
    {
        $hasil = Hasil::findOrFail($id);

        if ($request->status_laporan == 'diajukan') {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! Status masalah tidak dapat dianulir.'
            ], 400);
        }

        $hasil->status_laporan = $request->status_laporan;
        $hasil->save();

        Alert::success('Berhasil', 'Status laporan berhasil diperbarui');
        return back();
    }
}
