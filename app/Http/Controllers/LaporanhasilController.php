<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Prioritas;
use App\Models\SifatPekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

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

        $modal_hasil = Hasil::with('pekerjaan', 'pic', 'prioritas')->get();
        $modal_pekerjaan = Pekerjaan::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->get();

        if (Auth::user()->role == 'ASMEN') {
            $hasil = Hasil::with('pekerjaan', 'pic', 'prioritas')->orderBy('id', 'desc');
        } else {
            $hasil = Hasil::with('pekerjaan', 'pic', 'prioritas')->where('user_id', Auth::user()->id)->orderBy('id', 'desc');
        }

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
                'hasil' => $hasil->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                        'pic_name' => optional($item->pic)->name ?? '-',
                        'pic_divisi' => optional($item->pic)->getNameDivisi() ?? '-',
                        'pekerjaan_deskripsi' => optional($item->pekerjaan)->deskripsi_pekerjaan ?? '-',
                        'doc_laporan' => $item->doc_laporan,
                        'status_laporan' => $item->status_laporan,
                        'feedback' => $item->feedback,
                    ];
                }),
                'status_laporan' => ['diajukan', 'revisi', 'ditolak', 'disetujui'],
                'pekerjaan' => Pekerjaan::all()
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

        if ($doc_laporan = $request->file('doc_laporan')) {
            $path = 'Laporan Hasil/' . Auth::user()->name;

            // Buat folder jika belum ada
            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            $laporanHasilName = $doc_laporan->getClientOriginalName();
            $doc_laporan->move(public_path($path), $laporanHasilName);
        }

        Hasil::create([
            'user_id' => Auth::user()->id,
            'pekerjaan_id' => $request->pekerjaan_id,
            'status_laporan' => $request->status_laporan,
            'keterangan' => $request->keterangan,
            'doc_laporan' => $laporanHasilName ?? null
        ]);

        Alert::success('Berhasil', 'Laporan hasil berhasil ditambahkan');
        return redirect('laporan-hasil');
    }

    public function update(Request $request, $id)
    {
        $hasil = Hasil::findOrFail($id);

        $laporanHasilName = $hasil->doc_laporan;

        if ($request->hasFile('doc_laporan')) {
            $path = 'Laporan Hasil/' . Auth::user()->name;

            // Hapus file lama jika ada file baru yang diunggah
            if ($hasil->doc_laporan && File::exists($path . $hasil->doc_laporan)) {
                File::delete($path . $hasil->doc_laporan);
            }

            // Simpan file baru
            $doc_laporan = $request->file('doc_laporan');
            $laporanHasilName = $doc_laporan->getClientOriginalName();
            $doc_laporan->move($path, $laporanHasilName);
        }

        $hasil->update([
            'status_laporan' => $request->status_laporan,
            'keterangan' => $request->keterangan,
            'doc_laporan' => $laporanHasilName,
        ]);

        Alert::success('Berhasil', 'Laporan hasil berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        $hasil = Hasil::where('id', $id)->first();

        if ($hasil) {
            // Hapus file yang terkait dengan pekerjaan utama
            $filePathHasil = public_path('Laporan Hasil/' . Auth::user()->name . '/' . $hasil->doc_laporan);
            if (File::exists($filePathHasil)) {
                File::delete($filePathHasil);
            }

            // Hapus pekerjaan utama dari database
            $hasil->delete();

            Alert::success('Berhasil', 'Laporan hasil dan lampiran berhasil dihapus');
            return back();
        }

        Alert::error('Gagal!', 'Data tidak ditemukan');
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

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diperbarui'
        ], 200);
    }
}
