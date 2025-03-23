<?php

namespace App\Http\Controllers;

use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Prioritas;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\SubPekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LogharianController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        $sifat_pekerjaan = SifatPekerjaan::all();
        $kategori_pekerjaan = KategoriPekerjaan::all();
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();
        $user = User::where('nik', '!=', null)->get();
        $user_modal = User::where('nik', '!=', null)->get();

        $pekerjaan = Pekerjaan::with(['getUser', 'getKategoriPekerjaan', 'getSifatPekerjaan', 'getPrioritas', 'getStatusPekerjaan', 'getPjPekerjaan', 'getSubPekerjaan'])
            ->where('user_id', Auth::user()->id)
            ->orderBy('status_pekerjaan_id', 'ASC')
            ->orderBy('tanggal_mulai', 'ASC');

        if ($request->ajax()) {
            if ($request->has('pekerjaan')) {
                $pekerjaan->whereIn('kategori_pekerjaan_id', $request->pekerjaan);
            }
            if ($request->has('prioritas')) {
                $pekerjaan->whereIn('prioritas_id', $request->prioritas);
            }
            if ($request->has('pic')) {
                $pekerjaan->whereIn('user_id', $request->pic);
            }
            if ($request->has('tanggal')) {
                $dates = explode(" - ", $request->tanggal);

                if (count($dates) == 2) {
                    $startDate = date('Y-m-d', strtotime(trim($dates[0]))); // Konversi ke format Y-m-d
                    $endDate = date('Y-m-d', strtotime(trim($dates[1])));

                    $pekerjaan->whereBetween('tanggal_mulai', array($startDate, $endDate));
                }
            }

            return response()->json([
                'pekerjaan' => $pekerjaan->get(),
                'status_pekerjaan' => $status_pekerjaan
            ]);
        }

        $pekerjaan = $pekerjaan->get();

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
        $sifat_pekerjaan = SifatPekerjaan::all();
        $kategori_pekerjaan = KategoriPekerjaan::all();
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();
        $user = User::where('nik', '!=', null)->get();

        return view('log-harian.create', compact('sifat_pekerjaan', 'kategori_pekerjaan', 'user', 'prioritas', 'status_pekerjaan'));
    }

    public function store(Request $request)
    {
        $lampiranName = null;

        if ($lampiran = $request->file('lampiran')) {
            $path = 'lampiran/pekerjaan';
            $lampiranName = date('YmdHis') . '.' . $lampiran->getClientOriginalExtension();
            $lampiran->move($path, $lampiranName);
        }

        Pekerjaan::create([
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
            'deadline' => $request->deadline,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'alasan' => $request->alasan,
            'lampiran' => $lampiranName,
        ]);

        Alert::success('Berhasil', 'Pekerjan baru berhasil ditambahkan!');
        return redirect('log-harian');
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
            'deadline' => 'required|date',
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
            $lampiranName = date('YmdHis') . '.' . $lampiran->getClientOriginalExtension();
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

        if ($pekerjaan) {
            // Hapus semua sub pekerjaan terlebih dahulu
            $pekerjaan->getSubPekerjaan()->each(function ($subPekerjaan) {
                $subPekerjaan->delete();
            });

            // Hapus pekerjaan utama
            $pekerjaan->delete();

            Alert::success('Berhasil', 'Data pekerjaan berhasil dihapus');
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
            $lampiranName = date('YmdHis') . '.' . $lampiran->getClientOriginalExtension();
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
            'status_pekerjaan_id' => $request->status_pekerjaan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $request->durasi,
            'deadline' => $request->deadline,
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
            'deadline' => 'required|date',
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
            $lampiranName = date('YmdHis') . '.' . $lampiran->getClientOriginalExtension();
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
        $kerjaan = Pekerjaan::where('id', $id)->first();

        if (!$kerjaan) {
            return response()->json(['message' => 'Pekerjaan tidak ditemukan'], 404);
        }

        // Ambil semua SubPekerjaan terkait
        $subPekerjaan = SubPekerjaan::where('pekerjaan_id', $kerjaan->id)->get();

        // Cek apakah masih ada SubPekerjaan yang belum selesai (status_pekerjaan_id != 3)
        $adaBelumSelesai = $subPekerjaan->contains(function ($sub) {
            return $sub->status_pekerjaan_id != 3;
        });

        // Jika masih ada SubPekerjaan yang belum selesai dan ingin mengubah status ke 3, tolak update
        if ($adaBelumSelesai && $request->status_pekerjaan_id == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Update ditolak! Masih ada Sub Pekerjaan yang belum selesai.'
            ], 400);
        }

        // Jika tidak ada sub pekerjaan atau update bukan ke status 3, izinkan update
        $kerjaan->status_pekerjaan_id = $request->status_pekerjaan_id;
        $kerjaan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pekerjaan berhasil diperbarui'
        ]);
    }

    public function updateSubStatusPekerjaan(Request $request, $id)
    {
        $subPekerjaan = SubPekerjaan::where('id', $id)->first();

        $subPekerjaan->status_pekerjaan_id = $request->status_pekerjaan_id;
        $subPekerjaan->save();

        return response()->json(['message' => 'Status sub pekerjaan berhasil diperbarui']);
    }
}
