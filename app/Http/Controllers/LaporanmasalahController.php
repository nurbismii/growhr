<?php

namespace App\Http\Controllers;

use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Pengaduan;
use App\Models\Prioritas;
use App\Models\RiwayatPembaruanStatusPekerjaan;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanmasalahController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        $sifat_pekerjaan = SifatPekerjaan::orderBy('pekerjaan', 'asc')->get();;
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan', 'asc')->get();;
        $prioritas = Prioritas::orderBy('prioritas', 'asc')->get();;
        $status_pekerjaan = StatusPekerjaan::orderBy('status_pekerjaan', 'asc')->get();;
        $user = User::where('nik', '!=', null)->get();
        $pekerjaan = Pekerjaan::where('user_id', Auth::user()->id)->get();

        $pengaduan_modal = Pengaduan::with(['pekerjaan', 'pic', 'statusPekerjaan', 'prioritas'])->where('user_id', Auth::user()->id)->get();
        $pekerjaan_modal = Pekerjaan::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');    // Hari terakhir bulan ini

        if (Auth::user()->role == 'ASMEN') {
            $pengaduan = Pengaduan::with('pekerjaan', 'pic', 'statusPekerjaan', 'prioritas');
        } else {
            $pengaduan = Pengaduan::with('pekerjaan', 'pic', 'statusPekerjaan', 'prioritas')->where('user_id', Auth::user()->id);
        }

        if ($request->ajax()) {
            if ($request->has('kategori_kendala')) {
                $pengaduan->where('kategori_kendala', $request->kategori_kendala);
            }
            if ($request->has('prioritas')) {
                $pengaduan->where('prioritas_id', $request->prioritas);
            }
            if ($request->has('pic')) {
                $pengaduan->where('user_id', $request->pic);
            }
            if ($request->has('tanggal')) {
                $dates = explode(" - ", $request->tanggal);

                if (count($dates) == 2) {
                    $startDate = date('Y-m-d', strtotime(trim($dates[0]))); // Konversi ke format Y-m-d
                    $endDate = date('Y-m-d', strtotime(trim($dates[1])));

                    $pengaduan->whereBetween('created_at', array($startDate, $endDate));
                }
            }

            return response()->json([
                'pengaduan' => $pengaduan->get(),
                'status_pekerjaan' => $status_pekerjaan,
                'status_kendala' => [
                    'sedang-ditangani',
                    'terselesaikan'
                ],
            ]);
        }

        $pengaduan = $pengaduan->whereBetween('created_at', array($startDate, $endDate))->get();

        return view('laporan-masalah.index', compact(
            'pengaduan',
            'pengaduan_modal',
            'pekerjaan_modal',
            'sifat_pekerjaan',
            'kategori_pekerjaan',
            'user',
            'prioritas',
            'status_pekerjaan',
            'pekerjaan'
        ))->with('no');
    }

    public function create()
    {
        $pekerjaan = Pekerjaan::where('user_id', Auth::user()->id)->get();
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();

        return view('laporan-masalah.create', compact('pekerjaan', 'status_pekerjaan', 'prioritas'));
    }

    public function store(Request $request)
    {
        $permasalahFileName = null;
        $analisaFileName = null;
        $solusiFileName = null;

        // Upload file Permasalahan
        if ($doc_permasalahan_file = $request->file('doc_permasalahan')) {
            $path = 'Permasalahan/' . Auth::user()->name;

            // Buat folder jika belum ada
            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            $permasalahFileName = $doc_permasalahan_file->getClientOriginalName();
            $doc_permasalahan_file->move(public_path($path), $permasalahFileName);
        }

        // Upload file Analisa
        if ($doc_analisa_file = $request->file('doc_analisa')) {
            $path = 'Analisa/' . Auth::user()->name;

            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            $analisaFileName = $doc_analisa_file->getClientOriginalName();
            $doc_analisa_file->move(public_path($path), $analisaFileName);
        }

        // Upload file Solusi
        if ($doc_solusi_file = $request->file('doc_solusi')) {
            $path = 'Solusi/' . Auth::user()->name;

            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            $solusiFileName = $doc_solusi_file->getClientOriginalName();
            $doc_solusi_file->move(public_path($path), $solusiFileName);
        }

        Pengaduan::create([
            'pekerjaan_id' => $request->jenis_pekerjaan_id,
            'user_id' => Auth::user()->id,
            'prioritas_id' => $request->prioritas_id,
            'kategori_kendala' => $request->kategori_kendala,
            'status_kendala' => $request->status_pekerjaan_id,
            'alasan_tingkat_dampak_pengaduan' => $request->alasan_tingkat_dampak_pengaduan,
            'deskripsi_pengaduan' => $request->deskripsi_pengaduan,
            'langkah_penyelesaian' => $request->langkah_penyelesaian,
            'doc_permasalahan' => $permasalahFileName,
            'doc_analisis_risiko' => $analisaFileName,
            'doc_solusi' => $solusiFileName
        ]);

        Alert::success('Berhasil', 'Kendala berhasil ditambahkan!');
        return redirect()->route('laporan-masalah.index');
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $permasalahFileName = $pengaduan->doc_permasalahan;
        $analisaFileName = $pengaduan->doc_analisis_risiko;
        $solusiFileName = $pengaduan->doc_solusi;

        if ($request->hasFile('doc_permasalahan')) {
            $path = 'Permasalahan/' . Auth::user()->name;

            // Hapus file lama jika ada file baru yang diunggah
            if ($pengaduan->doc_permasalahan && File::exists($path . $pengaduan->doc_permasalahan)) {
                File::delete($path . $pengaduan->doc_permasalahan);
            }

            // Simpan file baru
            $doc_permasalahan_file = $request->file('doc_permasalahan');
            $permasalahFileName = $doc_permasalahan_file->getClientOriginalName();
            $doc_permasalahan_file->move($path, $permasalahFileName);
        }

        if ($request->hasFile('doc_analisa')) {
            $path = 'Analisa/' . Auth::user()->name;

            // Hapus file lama jika ada file baru yang diunggah
            if ($pengaduan->doc_analisis_risiko && File::exists($path . $pengaduan->doc_analisis_risiko)) {
                File::delete($path . $pengaduan->doc_analisis_risiko);
            }

            // Simpan file baru
            $doc_analisis_file = $request->file('doc_analisa');
            $analisaFileName = $doc_analisis_file->getClientOriginalName();
            $doc_analisis_file->move($path, $analisaFileName);
        }

        if ($request->hasFile('doc_solusi')) {
            $path = 'Solusi/' . Auth::user()->name;

            // Hapus file lama jika ada file baru yang diunggah
            if ($pengaduan->doc_solusi && File::exists($path . $pengaduan->doc_solusi)) {
                File::delete($path . $pengaduan->doc_solusi);
            }

            // Simpan file baru
            $doc_solusi_file = $request->file('doc_solusi');
            $solusiFileName = $doc_solusi_file->getClientOriginalName();
            $doc_solusi_file->move($path, $solusiFileName);
        }

        $pengaduan->update([
            'pekerjaan_id' => $request->jenis_pekerjaan_id,
            'user_id' => Auth::user()->id,
            'prioritas_id' => $request->prioritas_id,
            'kategori_kendala' => $request->kategori_kendala,
            'status_kendala' => $request->status_pekerjaan_id,
            'alasan_tingkat_dampak_pengaduan' => $request->alasan_tingkat_dampak_pengaduan,
            'deskripsi_pengaduan' => $request->deskripsi_pengaduan,
            'langkah_penyelesaian' => $request->langkah_penyelesaian,
            'doc_permasalahan' => $permasalahFileName,
            'doc_analisis_risiko' => $analisaFileName,
            'doc_solusi' => $solusiFileName,
        ]);

        Alert::success('Berhasil', 'Kendala berhasil diperbarui!');
        return redirect()->route('laporan-masalah.index');
    }


    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();

        if ($pengaduan) {

            $filePathPermasalahan = 'Permasalahan/' . Auth::user()->name . '/' . $pengaduan->doc_permasalahan;

            if (File::exists($filePathPermasalahan)) {
                File::delete($filePathPermasalahan);
            }

            $filePathAnalisa = 'Analisa/' . Auth::user()->name . '/' . $pengaduan->doc_analisis_risiko;

            if (File::exists($filePathAnalisa)) {
                File::delete($filePathAnalisa);
            }

            $filePathSolusi = 'Solusi/' . Auth::user()->name . '/' . $pengaduan->doc_solusi;

            if (File::exists($filePathSolusi)) {
                File::delete($filePathSolusi);
            }

            // Hapus pekerjaan utama dari database
            $pengaduan->delete();

            Alert::success('Berhasil', 'Data pekerjaan dan lampiran berhasil dihapus');
            return back();
        }

        Alert::error('Gagal!', 'Data tidak ditemukan');
        return back();
    }

    public function updateStatusPekerjaan(Request $request, $id)
    {
        $pengaduan = Pengaduan::with('statusPekerjaan')->where('id', $id)->first();

        if ($pengaduan->status_kendala == 'terselesaikan') {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! Status kendala tidak dapat dianulir.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            RiwayatPembaruanStatusPekerjaan::create([
                'pengaduan_id' => $pengaduan->id,
                'pembaruan' => date('Y-m-d H:i:s'),
                'status_pembaruan' => $request->status_pekerjaan_id,
            ]);

            $pengaduan->status_kendala = $request->status_pekerjaan_id;
            $pengaduan->save();

            DB::commit();
            return response()->json(['message' => 'Status laporan masalah berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 400);
        }
    }
}
