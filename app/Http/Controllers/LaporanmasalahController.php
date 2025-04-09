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
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $pengaduan = Pengaduan::with('pekerjaan', 'pic', 'statusPekerjaan', 'prioritas')->where('user_id', Auth::user()->id);

        $pengaduan_modal = Pengaduan::with(['pekerjaan', 'pic', 'statusPekerjaan', 'prioritas'])->where('user_id', Auth::user()->id)->get();
        $pekerjaan_modal = Pekerjaan::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

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

        $pengaduan = $pengaduan->get();

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
        $doc_analisis_file = null;
        $doc_permasalahan_file = null;
        $doc_solusi_file = null;

        if ($request->hasFile('doc_permasalahan')) {
            // Tentukan path penyimpanan relatif dalam storage/app/public
            $path = 'Permasalahan/' . Auth::user()->name . '/' . date('dmY');

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory($path);

            // Simpan file dengan nama asli ke dalam path yang benar
            $doc_permasalahan_file = $request->file('doc_permasalahan')->storeAs(
                $path, // Folder dalam `storage/app/public/`
                $request->file('doc_permasalahan')->getClientOriginalName(),
                'public' // Gunakan disk 'public'
            );
        }

        if ($request->hasFile('doc_analisa')) {
            // Tentukan path penyimpanan relatif dalam storage/app/public
            $path = 'Analisa/' . Auth::user()->name . '/' . date('dmY');

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory($path);

            // Simpan file dengan nama asli ke dalam path yang benar
            $doc_analisis_file = $request->file('doc_analisa')->storeAs(
                $path, // Folder dalam `storage/app/public/`
                $request->file('doc_analisa')->getClientOriginalName(),
                'public' // Gunakan disk 'public'
            );
        }

        if ($request->hasFile('doc_solusi')) {
            // Tentukan path penyimpanan relatif dalam storage/app/public
            $path = 'Solusi/' . Auth::user()->name . '/' . date('dmY');

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory($path);

            // Simpan file dengan nama asli ke dalam path yang benar
            $doc_solusi_file = $request->file('doc_solusi')->storeAs(
                $path, // Folder dalam `storage/app/public/`
                $request->file('doc_solusi')->getClientOriginalName(),
                'public' // Gunakan disk 'public'
            );
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
            'doc_permasalahan' => $doc_permasalahan_file,
            'doc_analisis_risiko' => $doc_analisis_file,
            'doc_solusi' => $doc_solusi_file
        ]);

        Alert::success('Berhasil', 'Kendala berhasil ditambahkan!');
        return redirect()->route('laporan-masalah.index');
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $doc_permasalahan_file = $pengaduan->doc_permasalahan;
        $doc_analisis_file = $pengaduan->doc_analisis_risiko;
        $doc_solusi_file = $pengaduan->doc_solusi;

        if ($request->hasFile('doc_permasalahan')) {
            // Hapus file lama jika ada
            if ($pengaduan->doc_permasalahan) {
                Storage::disk('public')->delete($pengaduan->doc_permasalahan);
            }

            $path = 'Permasalahan/' . Auth::user()->name . '/' . date('dmY');
            Storage::disk('public')->makeDirectory($path);
            $doc_permasalahan_file = $request->file('doc_permasalahan')->storeAs(
                $path,
                $request->file('doc_permasalahan')->getClientOriginalName(),
                'public'
            );
        }

        if ($request->hasFile('doc_analisa')) {
            // Hapus file lama jika ada
            if ($pengaduan->doc_analisis_risiko) {
                Storage::disk('public')->delete($pengaduan->doc_analisis_risiko);
            }

            $path = 'Analisa/' . Auth::user()->name . '/' . date('dmY');
            Storage::disk('public')->makeDirectory($path);
            $doc_analisis_file = $request->file('doc_analisa')->storeAs(
                $path,
                $request->file('doc_analisa')->getClientOriginalName(),
                'public'
            );
        }

        if ($request->hasFile('doc_solusi')) {
            // Hapus file lama jika ada
            if ($pengaduan->doc_solusi) {
                Storage::disk('public')->delete($pengaduan->doc_solusi);
            }

            $path = 'Solusi/' . Auth::user()->name . '/' . date('dmY');
            Storage::disk('public')->makeDirectory($path);
            $doc_solusi_file = $request->file('doc_solusi')->storeAs(
                $path,
                $request->file('doc_solusi')->getClientOriginalName(),
                'public'
            );
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
            'doc_permasalahan' => $doc_permasalahan_file,
            'doc_analisis_risiko' => $doc_analisis_file,
            'doc_solusi' => $doc_solusi_file
        ]);

        Alert::success('Berhasil', 'Kendala berhasil diperbarui!');
        return redirect()->route('laporan-masalah.index');
    }


    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();

        if ($pengaduan->doc_permasalahan) {
            Storage::disk('public')->delete($pengaduan->doc_permasalahan);
        }

        if ($pengaduan->doc_analisis_risiko) {
            Storage::disk('public')->delete($pengaduan->doc_analisis_risiko);
        }

        if ($pengaduan->doc_permasalahan) {
            Storage::disk('public')->delete($pengaduan->doc_permasalahan);
        }

        // Hapus pekerjaan utama dari database
        $pengaduan->delete();

        Alert::success('Berhasil', 'Data pekerjaan dan lampiran berhasil dihapus');
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
