<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Hris\EmployeeHris;
use App\Models\KategoriPelayanan;
use App\Models\Pelayanan;
use App\Models\SubKategoriPelayanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class PelayananController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        $kategori_pelayanan = KategoriPelayanan::orderBy('pelayanan', 'asc')->get();
        $sub_kategori_pelayanan = SubKategoriPelayanan::orderBy('sub_pelayanan', 'asc')->get();
        $user = User::where('nik', '!=', null)->get();
        $divisi = Divisi::all();
        $divisi_modal = Divisi::all();
        $employee_hris = EmployeeHris::with('getDivisi.getDepartemen')->select('nik', 'nama_karyawan', 'divisi_id')->where('status_resign', '!=', null)->get();

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');    // Hari terakhir bulan ini

        if (Auth::user()->role == "ASMEN") {
            $pelayanan = Pelayanan::with('getDivisi', 'kategoriPelayanan', 'subKategoriPelayanan', 'pic');
        } else {
            $pelayanan = Pelayanan::with('getDivisi', 'kategoriPelayanan', 'subKategoriPelayanan', 'pic')->where('id', Auth::user()->id);
        }

        if ($request->ajax()) {
            if ($request->has('divisi_id')) {
                $pelayanan->where('divisi_id', $request->divisi_id);
            }
            if ($request->has('kategori_pelayanan_id')) {
                $pelayanan->where('kategori_pelayanan_id', $request->kategori_pelayanan_id);
            }
            if ($request->has('pic')) {
                $pelayanan->where('user_id', $request->pic);
            }
            if ($request->has('tanggal')) {
                $dates = explode(" - ", $request->tanggal);

                if (count($dates) == 2) {
                    $startDate = date('Y-m-d', strtotime(trim($dates[0]))); // Konversi ke format Y-m-d
                    $endDate = date('Y-m-d', strtotime(trim($dates[1])));

                    $pelayanan->whereBetween('created_at', array($startDate, $endDate));
                }
            }

            return response()->json([
                'pelayanan' => $pelayanan->get()
            ]);
        }

        $pelayanan = $pelayanan->whereBetween('created_at', array($startDate, $endDate))->get();

        return view('pelayanan.index', compact(
            'pelayanan',
            'divisi',
            'divisi_modal',
            'user',
            'kategori_pelayanan',
            'sub_kategori_pelayanan',
            'employee_hris'
        ))->with('no');
    }

    public function create()
    {
        $divisi = Divisi::all();
        $kategori_pelayanan = KategoriPelayanan::orderBy('pelayanan', 'asc')->get();
        $sub_kategori_pelayanan = SubKategoriPelayanan::orderBy('sub_pelayanan', 'asc')->get();
        $employee_hris = EmployeeHris::select('nik', 'nama_karyawan')->where('status_resign', '!=', null)->get();

        return view('pelayanan.create', compact(
            'divisi',
            'kategori_pelayanan',
            'sub_kategori_pelayanan',
            'employee_hris'
        ));
    }

    public function store(Request $request)
    {
        $doc_pendukung = null;

        if ($request->hasFile('doc_pendukung')) {
            // Tentukan path tujuan di folder public
            $path = public_path('Laporan pelayanan/' . Auth::user()->name);

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $docPendukungFile = $request->file('doc_pendukung');
            $doc_pendukung = $docPendukungFile->getClientOriginalName();

            $docPendukungFile->move($path, $doc_pendukung);
        }

        Pelayanan::create([
            'user_id' => Auth::user()->id,
            'divisi_id' => $request->divisi_id,
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_kategori_pelayanan_id' => $request->sub_kategori_pelayanan_id,
            'nik_karyawan' => $request->nik_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'departemen' => $request->departemen,
            'divisi' => $request->divisi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'durasi' => $request->durasi,
            'keterangan' => $request->keterangan,
            'doc_pendukung' => $doc_pendukung
        ]);

        Alert::success('Berhasil', 'Laporan pelayanan berhasil ditambahkan');
        return redirect()->route('laporan-pelayanan.index');
    }

    public function update(Request $request, $id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        $laporanHasilName = $pelayanan->doc_pendukung;

        if ($request->hasFile('doc_pendukung')) {
            $path = 'Laporan Pelayanan/' . Auth::user()->name;

            // Hapus file lama jika ada file baru yang diunggah
            if ($pelayanan->doc_pendukung && File::exists($path . $pelayanan->doc_pendukung)) {
                File::delete($path . $pelayanan->doc_pendukung);
            }

            // Simpan file baru
            $doc_laporan = $request->file('doc_pendukung');
            $laporanHasilName = $doc_laporan->getClientOriginalName();
            $doc_laporan->move($path, $laporanHasilName);
        }

        $pelayanan->update([
            'user_id' => Auth::user()->id,
            'divisi_id' => $request->divisi_id,
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_kategori_pelayanan_id' => $request->sub_kategori_pelayanan_id,
            'nik_karyawan' => $request->nik_karyawan,
            'departemen' => $request->departemen,
            'divisi' => $request->divisi,
            'nama_karyawan' => $request->nama_karyawan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'durasi' => $request->durasi,
            'keterangan' => $request->keterangan,
            'doc_pendukung' => $laporanHasilName
        ]);

        Alert::success('Berhasil', 'Laporan pelayanan berhasil diperbarui');
        return redirect()->route('laporan-pelayanan.index');
    }

    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        if ($pelayanan) {
            // Hapus file yang terkait dengan pekerjaan utama
            $filePathHasil = public_path('Laporan Hasil/' . Auth::user()->name . '/' . $pelayanan->doc_pendukung);
            if (File::exists($filePathHasil)) {
                File::delete($filePathHasil);
            }

            // Hapus pekerjaan utama dari database
            $pelayanan->delete();

            Alert::success('Berhasil', 'Laporan pelayanan berhasil dihapus');
            return back();
        }
        Alert::error('Gagal!', 'Data tidak ditemukan');
        return back();
    }

    public function getKategori($divisi_id)
    {
        $kategori = KategoriPelayanan::where('divisi_id', $divisi_id)->get();
        return response()->json($kategori);
    }

    public function getSubKategori($id)
    {
        $subKategoris = SubKategoriPelayanan::where('kategori_pelayanan_id', $id)->orderBy('sub_pelayanan', 'asc')->get();
        return response()->json($subKategoris);
    }

    public function getNamaKaryawan($nik)
    {
        $employee = EmployeeHris::with('getDivisi.getDepartemen')->select('nama_karyawan', 'divisi_id')->where('nik', $nik)->first();
        return response()->json($employee);
    }
}
