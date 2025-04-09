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
use Illuminate\Support\Facades\Storage;
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

        $pelayanan = $pelayanan->get();

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
            // Tentukan path penyimpanan relatif dalam storage/app/public
            $path = 'Laporan pelayanan/' . Auth::user()->name . '/' . date('dmY');

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory($path);

            // Simpan file dengan nama asli ke dalam path yang benar
            $doc_pendukung = $request->file('doc_pendukung')->storeAs(
                $path, // Folder dalam `storage/app/public/`
                $request->file('doc_pendukung')->getClientOriginalName(),
                'public' // Gunakan disk 'public'
            );
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

        if ($request->hasFile('doc_laporan')) {
            // Hapus file lama jika ada
            if ($pelayanan->doc_pendukung) {
                Storage::disk('public')->delete($pelayanan->doc_pendukung);
            }

            $path = 'Laporan pelayanan/' . Auth::user()->name . '/' . date('dmY');
            Storage::disk('public')->makeDirectory($path);
            $doc_laporan = $request->file('doc_laporan')->storeAs(
                $path,
                $request->file('doc_laporan')->getClientOriginalName(),
                'public'
            );
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
            'doc_pendukung' => $doc_laporan
        ]);

        Alert::success('Berhasil', 'Laporan pelayanan berhasil diperbarui');
        return redirect()->route('laporan-pelayanan.index');
    }

    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);

        if ($pelayanan->doc_pendukung) {
            Storage::disk('public')->delete($pelayanan->doc_pendukung);
        }

        $pelayanan->delete();

        Alert::success('Berhasil', 'Laporan pelayanan berhasil dihapus');
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
