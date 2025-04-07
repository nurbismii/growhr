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
use RealRashid\SweetAlert\Facades\Alert;

class PelayananController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus data!';
        $text = 'Kamu yakin ingin menghapus data ini ?';
        confirmDelete($title, $text);

        $pelayanan = Pelayanan::with('divisi', 'kategoriPelayanan', 'subKategoriPelayanan', 'pic');
        $kategori_pelayanan = KategoriPelayanan::orderBy('pelayanan', 'asc')->get();
        $sub_kategori_pelayanan = SubKategoriPelayanan::orderBy('sub_pelayanan', 'asc')->get();
        $user = User::all();
        $divisi = Divisi::all();
        $divisi_modal = Divisi::all();
        $employee_hris = EmployeeHris::select('nik', 'nama_karyawan')->where('status_resign', '!=', null)->get();

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
        Pelayanan::create([
            'user_id' => Auth::user()->id,
            'divisi_id' => $request->divisi_id,
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_kategori_pelayanan_id' => $request->sub_kategori_pelayanan_id,
            'nik_karyawan' => $request->nik_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'durasi' => $request->durasi,
            'keterangan' => $request->keterangan
        ]);

        Alert::success('Berhasil', 'Laporan pelayanan berhasil ditambahkan');
        return redirect()->route('laporan-pelayanan.index');
    }

    public function update(Request $request, $id)
    {
        Pelayanan::where('id', $id)->update([
            'user_id' => Auth::user()->id,
            'divisi_id' => $request->divisi_id,
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_kategori_pelayanan_id' => $request->sub_kategori_pelayanan_id,
            'nik_karyawan' => $request->nik_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'durasi' => $request->durasi,
            'keterangan' => $request->keterangan
        ]);

        Alert::success('Berhasil', 'Laporan pelayanan berhasil diperbarui');
        return redirect()->route('laporan-pelayanan.index');
    }

    public function destroy($id)
    {
        $pelayanan = Pelayanan::findOrFail($id);
        $pelayanan->delete();

        Alert::success('Berhasil', 'Laporan pelayanan berhasil dihapus');
        return back();
    }

    public function getSubKategori(Request $request)
    {
        $subKategoris = SubKategoriPelayanan::where('kategori_pelayanan_id', $request->kategori_pelayanan_id)->orderBy('sub_pelayanan', 'asc')->get();
        return response()->json($subKategoris);
    }

    public function getNamaKaryawan($nik)
    {
        $employee = EmployeeHris::select('nama_karyawan')->where('nik', $nik)->first();
        return response()->json($employee);
    }
}
