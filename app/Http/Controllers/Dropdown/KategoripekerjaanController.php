<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\KategoriPekerjaan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoripekerjaanController extends Controller
{
    public function index()
    {
        $kategori_pekerjaan = KategoriPekerjaan::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.kategori-pekerjaan.index', compact('kategori_pekerjaan'))->with('no');
    }

    public function create()
    {
        return view('dropdown.kategori-pekerjaan.create');
    }

    public function store(Request $request)
    {
        KategoriPekerjaan::create([
            'kategori_pekerjaan' => ucwords($request->kategori_pekerjaan),
            'deskripsi_pekerjaan' => ucfirst($request->deskripsi_pekerjaan),
        ]);

        Alert::success('Berhasil', 'Kategori pekerjaan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $kategori = KategoriPekerjaan::where('id', $id)->first();

        return view('dropdown.kategori-pekerjaan.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        KategoriPekerjaan::where('id', $id)->update([
            'kategori_pekerjaan' => ucwords($request->kategori_pekerjaan),
            'deskripsi_pekerjaan' => ucfirst($request->deskripsi_pekerjaan),
        ]);

        Alert::success('Berhasil', 'Kategori pekerjaan berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        KategoriPekerjaan::where('id', $id)->delete();

        Alert::success('Berhasil', 'Kategori pekerjaan berhasil dihapus');
        return back();
    }
}
