<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Pengaduan;
use App\Models\Prioritas;
use App\Models\RiwayatPembaruanStatusPekerjaan;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\SubPekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class LogharianController extends Controller
{
    public function index(Request $request)
    {
        confirmDelete('Hapus data!', 'Kamu yakin ingin menghapus data ini ?');

        $sifat_pekerjaan = SifatPekerjaan::orderBy('pekerjaan')->get();
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan')->get();
        $prioritas = Prioritas::orderBy('prioritas')->get();
        $status_pekerjaan = StatusPekerjaan::orderBy('status_pekerjaan')->get();
        $user = $user_modal = User::whereNotNull('nik')->orderBy('name')->get();

        // Base query
        $pekerjaanQuery = Pekerjaan::with([
            'getUser',
            'getKategoriPekerjaan',
            'getSifatPekerjaan',
            'getPrioritas',
            'getStatusPekerjaan',
            'getPjPekerjaan',
            'getSubPekerjaan'
        ])
            ->orderBy('status_pekerjaan_id')
            ->orderByDesc('tingkat_kesulitan');

        // Filter role
        if (!in_array(Auth::user()->role, ['ASMEN', 'ADMIN'])) {
            $pekerjaanQuery->where('user_id', Auth::id());
        }

        // Filter jika ajax
        if ($request->ajax()) {
            if ($request->filled('pekerjaan')) {
                $pekerjaanQuery->whereIn('id', $request->pekerjaan);
            }
            if ($request->filled('kategori_pekerjaan')) {
                $pekerjaanQuery->whereIn('kategori_pekerjaan_id', $request->kategori_pekerjaan);
            }
            if ($request->filled('prioritas')) {
                $pekerjaanQuery->whereIn('prioritas_id', $request->prioritas);
            }
            if ($request->filled('pic')) {
                $pekerjaanQuery->whereIn('user_id', $request->pic);
            }
            if ($request->filled('tanggal')) {
                $dates = explode(" - ", $request->tanggal);
                if (count($dates) === 2) {
                    $pekerjaanQuery->whereBetween('tanggal_mulai', [
                        date('Y-m-d', strtotime(trim($dates[0]))),
                        date('Y-m-d', strtotime(trim($dates[1])))
                    ]);
                }
            }

            return response()->json([
                'pekerjaan' => $pekerjaanQuery->get(),
                'status_pekerjaan' => $status_pekerjaan
            ]);
        }

        $pekerjaan = $pekerjaanQuery->get();

        return view('log-harian.index', compact(
            'pekerjaan',
            'sifat_pekerjaan',
            'kategori_pekerjaan',
            'user',
            'user_modal',
            'prioritas',
            'status_pekerjaan'
        ))->with('no');
    }

    public function create()
    {
        $sifat_pekerjaan = SifatPekerjaan::orderBy('created_at', 'asc')->get();
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan', 'asc')->get();
        $prioritas = Prioritas::orderBy('prioritas', 'asc')->get();
        $status_pekerjaan = StatusPekerjaan::orderBy('status_pekerjaan', 'asc')->get();
        $user = User::where('nik', '!=', null)->orderBy('name', 'asc')->get();

        return view('log-harian.create', compact('sifat_pekerjaan', 'kategori_pekerjaan', 'user', 'prioritas', 'status_pekerjaan'));
    }

    public function edit($id)
    {
        $pekerjaan = Pekerjaan::with(['getUser', 'getKategoriPekerjaan', 'getSifatPekerjaan', 'getPrioritas', 'getStatusPekerjaan', 'getPjPekerjaan', 'getSubPekerjaan'])
            ->where('id', $id)->first();

        $sifat_pekerjaan = SifatPekerjaan::orderBy('created_at', 'asc')->get();
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan', 'asc')->get();
        $prioritas = Prioritas::orderBy('prioritas', 'asc')->get();
        $status_pekerjaan = StatusPekerjaan::orderBy('status_pekerjaan', 'asc')->get();
        $user = User::where('nik', '!=', null)->orderBy('name', 'asc')->get();

        return view('log-harian.edit', compact('pekerjaan', 'sifat_pekerjaan', 'kategori_pekerjaan', 'user', 'prioritas', 'status_pekerjaan'));
    }

    public function subPekerjaanEdit($id)
    {
        $subpk = SubPekerjaan::with(['getUser', 'getKategoriPekerjaan', 'getSifatPekerjaan', 'getPrioritas', 'getStatusPekerjaan', 'getPjPekerjaan', 'pekerjaanHasOne'])
            ->where('id', $id)->first();

        $sifat_pekerjaan = SifatPekerjaan::orderBy('created_at', 'asc')->get();
        $kategori_pekerjaan = KategoriPekerjaan::orderBy('kategori_pekerjaan', 'asc')->get();
        $prioritas = Prioritas::orderBy('prioritas', 'asc')->get();
        $status_pekerjaan = StatusPekerjaan::orderBy('status_pekerjaan', 'asc')->get();
        $user = User::where('nik', '!=', null)->orderBy('name', 'asc')->get();

        return view('log-harian.sub-pekerjaan.edit', compact('subpk', 'sifat_pekerjaan', 'kategori_pekerjaan', 'user', 'prioritas', 'status_pekerjaan'));
    }

    public function store(Request $request)
    {
        $lampiranName = null;

        if ($lampiran = $request->file('lampiran')) {
            $path = 'lampiran/pekerjaan';
            $lampiranName = $lampiran->getClientOriginalName();
            $lampiran->move($path, $lampiranName);
        }

        try {
            DB::beginTransaction();
            $kerjaan = Pekerjaan::create([
                'user_id' => Auth::user()->id,
                'sifat_pekerjaan_id' => $request->sifat_pekerjaan,
                'kategori_pekerjaan_id' => $request->kategori_pekerjaan_id,
                'pj_pekerjaan_id' => $request->pj_pekerjaan_id,
                'prioritas_id' => $request->prioritas_id,
                'status_pekerjaan_id' => $request->status_pekerjaan_id,
                'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi' => $request->durasi,
                'tingkat_kesulitan' => $request->tingkat_kesulitan,
                'alasan' => $request->alasan,
                'lampiran' => $lampiranName,
            ]);

            $status = StatusPekerjaan::where('id', $kerjaan->status_pekerjaan_id)->first();

            RiwayatPembaruanStatusPekerjaan::create([
                'pekerjaan_id' => $kerjaan->id,
                'pembaruan' => date('Y-m-d H:i:s'),
                'status_pembaruan' => $status->status_pekerjaan
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Pekerjan baru berhasil ditambahkan!');
            return redirect('log-harian');
        } catch (\Exception $e) {
            DB::rollBack();

            Alert::success('Error', 'Terjadi kesalahan');
            return redirect('log-harian');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sifat_pekerjaan' => 'required',
            'kategori_pekerjaan_id' => 'required',
            'pj_pekerjaan_id' => 'required',
            'prioritas_id' => 'required',
            'status_pekerjaan_id' => 'required',
            'deskripsi_pekerjaan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'durasi' => 'required',
            'tingkat_kesulitan' => 'required|numeric|min:1|max:10',
            'alasan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Ambil data pekerjaan berdasarkan ID
        $pekerjaan = Pekerjaan::findOrFail($id);

        // Inisialisasi nama file lampiran (tetap pakai yang lama jika tidak ada file baru)
        $lampiranName = $pekerjaan->lampiran;

        if ($request->hasFile('lampiran')) {
            $path = 'lampiran/pekerjaan/';

            // Hapus file lama jika ada file baru yang diunggah
            if ($pekerjaan->lampiran && File::exists($path . $pekerjaan->lampiran)) {
                File::delete($path . $pekerjaan->lampiran);
            }

            // Simpan file baru
            $lampiran = $request->file('lampiran');
            $lampiranName = $lampiran->getClientOriginalName();
            $lampiran->move($path, $lampiranName);
        }

        // Update data pekerjaan
        $pekerjaan->update([
            'sifat_pekerjaan_id' => $request->sifat_pekerjaan,
            'kategori_pekerjaan_id' => $request->kategori_pekerjaan_id,
            'pj_pekerjaan_id' => $request->pj_pekerjaan_id,
            'prioritas_id' => $request->prioritas_id,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $request->durasi,
            'deadline' => $request->deadline,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'alasan' => $request->alasan,
            'lampiran' => $lampiranName,
        ]);

        Alert::success('Berhasil', 'Pekerjaan berhasil diperbarui!');
        return redirect('log-harian');
    }


    public function destroy($id)
    {
        $pekerjaan = Pekerjaan::with('getSubPekerjaan')->where('id', $id)->first();

        $hasil = Hasil::where('pekerjaan_id', $pekerjaan->id)->first();

        $pengaduan = Pengaduan::where('pekerjaan_id', $pekerjaan->id)->first();

        if ($hasil) {
            // Hapus file yang terkait dengan pekerjaan utama
            $filePathHasil = public_path('Laporan Hasil/' . Auth::user()->name . '/' . $hasil->doc_laporan);
            if (File::exists($filePathHasil)) {
                File::delete($filePathHasil);
            }
            $hasil->delete();
        }

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

            $pengaduan->delete();
        }


        if ($pekerjaan) {
            // Hapus file yang terkait dengan pekerjaan utama
            $filePathPekerjaan = public_path('lampiran/pekerjaan/' . $pekerjaan->lampiran);
            if (File::exists($filePathPekerjaan)) {
                File::delete($filePathPekerjaan);
            }

            // Hapus semua sub pekerjaan dan file terkait
            $pekerjaan->getSubPekerjaan()->each(function ($subPekerjaan) {
                $filePathSub = public_path('lampiran/sub/pekerjaan/' . $subPekerjaan->lampiran);
                if (File::exists($filePathSub)) {
                    File::delete($filePathSub);
                }

                // Hapus data sub pekerjaan dari database
                $subPekerjaan->delete();
            });

            // Hapus pekerjaan utama dari database
            $pekerjaan->delete();

            Alert::success('Berhasil', 'Data pekerjaan dan lampiran berhasil dihapus');
            return back();
        }
    }

    # Sub Pekerjaan 

    public function subPekerjaanStore(Request $request)
    {
        $lampiranName = null;

        $pekerjaan = Pekerjaan::where('id', $request->pekerjaan_id)->first();

        if ($lampiran = $request->file('lampiran')) {
            $path = 'lampiran/' . $pekerjaan->id . '/sub';
            $lampiranName = $lampiran->getClientOriginalName();
            $lampiran->move($path, $lampiranName);
        }

        SubPekerjaan::create([
            'user_id' => Auth::user()->id,
            'pekerjaan_id' => $pekerjaan->id,
            'sifat_pekerjaan_id' => $pekerjaan->sifat_pekerjaan_id,
            'kategori_pekerjaan_id' => $request->kategori_pekerjaan_id,
            'pj_pekerjaan_id' => $request->pj_pekerjaan_id,
            'prioritas_id' => $request->prioritas_id,
            'deskripsi_pekerjaan' => $pekerjaan->deskripsi_pekerjaan,
            'sub_deskripsi_pekerjaan' => $request->sub_deskripsi_pekerjaan,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $request->durasi,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'alasan' => $request->alasan,
            'lampiran' => $lampiranName,
        ]);

        Alert::success('Berhasil', 'Sub pekerjaan berhasil ditambahkan');
        return back();
    }

    public function subPekerjaanUpdate(Request $request, $id)
    {
        $request->validate([
            'sifat_pekerjaan' => 'required',
            'kategori_pekerjaan_id' => 'required',
            'pj_pekerjaan_id' => 'required',
            'prioritas_id' => 'required',
            'status_pekerjaan_id' => 'required',
            'deskripsi_pekerjaan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'durasi' => 'required',
            'tingkat_kesulitan' => 'required|numeric|min:1|max:10',
            'alasan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Ambil data pekerjaan berdasarkan ID
        $subPekerjaan = SubPekerjaan::findOrFail($id);

        // Inisialisasi nama file lampiran (tetap pakai yang lama jika tidak ada file baru)
        $lampiranName = $subPekerjaan->lampiran;

        if ($request->hasFile('lampiran')) {
            $path = 'lampiran/pekerjaan/sub/';

            // Hapus file lama jika ada file baru yang diunggah
            if ($subPekerjaan->lampiran && File::exists($path . $subPekerjaan->lampiran)) {
                File::delete($path . $subPekerjaan->lampiran);
            }

            // Simpan file baru
            $lampiran = $request->file('lampiran');
            $lampiranName = $lampiran->getClientOriginalName();
            $lampiran->move($path, $lampiranName);
        }

        // Update data pekerjaan
        $subPekerjaan->update([
            'sifat_pekerjaan_id' => $request->sifat_pekerjaan,
            'kategori_pekerjaan_id' => $request->kategori_pekerjaan_id,
            'pj_pekerjaan_id' => $request->pj_pekerjaan_id,
            'prioritas_id' => $request->prioritas_id,
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,
            'sub_deskripsi_pekerjaan' => $request->sub_deskripsi_pekerjaan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $request->durasi,
            'deadline' => $request->deadline,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'alasan' => $request->alasan,
            'lampiran' => $lampiranName,
        ]);

        Alert::success('Berhasil', 'Pekerjaan berhasil diperbarui!');
        return redirect('log-harian');
    }

    public function subPekerjaanDestroy($id)
    {
        SubPekerjaan::where('id', $id)->delete();

        Alert::success('Berhasil', 'Data sub pekerjaan berhasil dihapus');
        return back();
    }

    public function getSubPekerjaan($id)
    {
        $subPekerjaan = SubPekerjaan::with(['getUser', 'pekerjaan', 'getStatusPekerjaan', 'getKategoriPekerjaan', 'getPjPekerjaan', 'getSifatPekerjaan', 'getPrioritas'])
            ->where('pekerjaan_id', $id)
            ->get();

        return response()->json($subPekerjaan);
    }

    public function updateStatusPekerjaan(Request $request, $id)
    {
        $belum_mulai = 1;
        $selesai = 5;
        $selesai_diterima = 7;

        $kerjaan = Pekerjaan::with('getStatusPekerjaan')->where('id', $id)->first();

        if (!$kerjaan) {
            return response()->json(['message' => 'Pekerjaan tidak ditemukan'], 404);
        }

        if ($kerjaan->status_pekerjaan_id == $selesai_diterima) {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! status selesai tidak dapat dianulir'
            ], 400);
        }

        if ($request->status_pekerjaan_id == $belum_mulai) {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! tidak dapat diproses.'
            ], 400);
        }

        // Ambil semua SubPekerjaan terkait
        $subPekerjaan = SubPekerjaan::where('pekerjaan_id', $kerjaan->id)->get();

        // Cek apakah masih ada SubPekerjaan yang belum memiliki status 5 atau 7
        $adaBelumSelesai = $subPekerjaan->contains(function ($sub) {
            return $sub->status_pekerjaan_id != 5 && $sub->status_pekerjaan_id != 7;
        });

        // Jika masih ada SubPekerjaan yang belum selesai, tolak update
        if ($adaBelumSelesai && ($request->status_pekerjaan_id == $selesai) || ($request->status_pekerjaan_id == $selesai_diterima)) {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! Masih ada Sub Pekerjaan yang belum selesai.'
            ], 400);
        }

        DB::beginTransaction();

        $status = StatusPekerjaan::where('id',  $request->status_pekerjaan_id)->first();

        try {
            RiwayatPembaruanStatusPekerjaan::create([
                'pekerjaan_id' => $kerjaan->id,
                'pembaruan' => date('Y-m-d H:i:s'),
                'status_pembaruan' => $status->status_pekerjaan
            ]);

            // Jika tidak ada sub pekerjaan atau update bukan ke status selesai, izinkan update
            $kerjaan->status_pekerjaan_id = $request->status_pekerjaan_id;
            $kerjaan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pekerjaan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat melakukan perubahan'
            ]);
        }
    }

    public function updateSubStatusPekerjaan(Request $request, $id)
    {
        $subPekerjaan = SubPekerjaan::with('getStatusPekerjaan')->where('id', $id)->first();

        DB::beginTransaction();

        $status = StatusPekerjaan::where('id',  $request->status_pekerjaan_id)->first();

        try {
            RiwayatPembaruanStatusPekerjaan::create([
                'sub_pekerjaan_id' => $subPekerjaan->id,
                'pembaruan' => date('Y-m-d H:i:s'),
                'status_pembaruan' => $status->status_pekerjaan,
            ]);

            if ($subPekerjaan->status_pekerjaan_id == 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Update ditolak! status sub selesai dan diterima tidak dapat dianulir '
                ], 400);
            }

            $subPekerjaan->status_pekerjaan_id = $request->status_pekerjaan_id;
            $subPekerjaan->save();

            DB::commit();
            return response()->json(['message' => 'Status sub pekerjaan berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Terjadi kesalahan']);
        }
    }

    public function updateWarnaStatus(Request $request, $id)
    {
        $warna = $request->input('warna_status');

        // Coba update ke Pekerjaan dulu
        $updated = Pekerjaan::where('id', $id)->update(['warna_status' => $warna]);

        // Kalau tidak ada di Pekerjaan, coba SubPekerjaan
        $updated = SubPekerjaan::where('id', $id)->update(['warna_status' => $warna]);

        if ($updated) {
            return response()->json(['message' => 'Warna status updated']);
        } else {
            return response()->json(['message' => 'ID not found'], 404);
        }
    }

    public function byUser($user)
    {
        return Pekerjaan::where('user_id', $user)
            ->select('id', 'deskripsi_pekerjaan')
            ->get();
    }
}
