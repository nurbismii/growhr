<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Pengaduan;
use App\Models\Prioritas;
use App\Models\SifatPekerjaan;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanmasalahController extends Controller
{
    public function index()
    {
        $sifat_pekerjaan = SifatPekerjaan::all();
        $kategori_pekerjaan = KategoriPekerjaan::all();
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();
        $user = User::where('nik', '!=', null)->get();

        return view('laporan-masalah.index', compact(
            'sifat_pekerjaan',
            'kategori_pekerjaan',
            'user',
            'prioritas',
            'status_pekerjaan'
        ));
    }

    public function create()
    {
        $pekerjaan = Pekerjaan::where('user_id', Auth::user()->id)->get();;
        $prioritas = Prioritas::all();
        $status_pekerjaan = StatusPekerjaan::all();

        return view('laporan-masalah.create', compact('pekerjaan', 'status_pekerjaan', 'prioritas'));
    }

    public function store(Request $request)
    {
        $doc_analisis_file = null;
        $doc_permasalahan_file = null;
        $doc_solusi_file = null;

        if ($doc_permasalahan = $request->file('doc_permasalahan')) {
            $path = 'doc_permasalahan/' . Auth::user()->name;
            $doc_permasalahan_file = date('YmdHis') . '.' . $doc_permasalahan->getClientOriginalExtension();
            $doc_permasalahan->move($path, $doc_permasalahan);
        }

        if ($doc_analisis = $request->file('doc_analisis')) {
            $path = 'doc_analisis/' . Auth::user()->name;
            $doc_analisis_file = date('YmdHis') . '.' . $doc_analisis->getClientOriginalExtension();
            $doc_permasalahan->move($path, $doc_permasalahan);
        }

        if ($doc_solusi = $request->file('doc_solusi')) {
            $path = 'doc_solusi/' . Auth::user()->name;
            $doc_solusi_file = date('YmdHis') . '.' . $doc_solusi->getClientOriginalExtension();
            $doc_solusi->move($path, $doc_solusi);
        }

        Pengaduan::create([
            'pekerjaan_id' => $request->jenis_pekerjaan_id,
            'kategori_kendala' => $request->kategori_kendala,
            'alasan_tingkat_dampak_pengaduan' => $request->alasan_tingkat_dampak_pengaduan,
            'deskripsi_pengaduan' => $request->deskripsi_pengaduan,
            'langkah_penyelesaian' => $request->langkah_penyelesaian,
            'doc_permasalahan' => $doc_permasalahan_file,
            'doc_analisis' => $doc_analisis_file,
            'doc_solusi' => $doc_solusi_file,
        ]);

        Alert::success('Berhasil', 'Pengaduan berhasil ditambahkan!');
        return back();
    }
}
