<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use App\Models\SifatPekerjaan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class JenispekerjaanController extends Controller
{
    public function index()
    {
        $pekerjaan = SifatPekerjaan::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.jenis-pekerjaan.index', compact('pekerjaan'))->with('no');
    }

    public function create()
    {
        return view('dropdown.jenis-pekerjaan.create');
    }

    public function store(Request $request)
    {
        SifatPekerjaan::create([
            'pekerjaan' => ucwords($request->jenis_pekerjaan),
            'deskripsi_pekerjaan' => ucfirst($request->deskripsi_pekerjaan),
        ]);

        Alert::success('Berhasil', 'Jenis pekerjaan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $pekerjaan = SifatPekerjaan::where('id', $id)->first();

        return view('dropdown.jenis-pekerjaan.edit', compact('pekerjaan'));
    }

    public function update(Request $request, $id)
    {
        SifatPekerjaan::where('id', $id)->update([
            'pekerjaan' => ucwords($request->jenis_pekerjaan),
            'deskripsi_pekerjaan' => ucfirst($request->deskripsi_pekerjaan),
        ]);

        Alert::success('Berhasil', 'jenis pekerjaan berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        SifatPekerjaan::where('id', $id)->delete();

        Alert::success('Berhasil', 'Jenis pekerjaan berhasil dihapus');
        return back();
    }
}
