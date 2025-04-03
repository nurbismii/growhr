<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelayanan;
use App\Models\SubKategoriPelayanan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SubKategoriPelayananController extends Controller
{
    public function index()
    {
        $sub_kategori_pelayanan = SubKategoriPelayanan::with('kategoriPelayanan')->get();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.sub-kategori-pelayanan.index', compact('sub_kategori_pelayanan'))->with('no');
    }

    public function create()
    {
        $kategori_pelayanan = KategoriPelayanan::all();

        return view('dropdown.sub-kategori-pelayanan.create', compact('kategori_pelayanan'));
    }

    public function store(Request $request)
    {
        SubKategoriPelayanan::create([
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_pelayanan' => $request->sub_kategori_pelayanan
        ]);

        Alert::success('Berhasil', 'Sub Kategori Pelayanan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $kategori_pelayanan = KategoriPelayanan::all();
        $sub_kategori_pelayanan = SubKategoriPelayanan::findOrFail($id);

        return view('dropdown.sub-kategori-pelayanan.edit', compact('kategori_pelayanan', 'sub_kategori_pelayanan'));
    }

    public function update(Request $request, $id)
    {
        $kategori_pelayanan = SubKategoriPelayanan::findOrFail($id);

        $kategori_pelayanan->update([
            'kategori_pelayanan_id' => $request->kategori_pelayanan_id,
            'sub_pelayanan' => $request->sub_kategori_pelayanan
        ]);

        Alert::success('Berhasil', 'Sub Pelayanan berhasil diperbarui');
        return redirect()->route('sub-kategori-pelayanan.index');
    }

    public function destroy($id)
    {
        $sub_kategori_pelayanan = SubKategoriPelayanan::findOrFail($id);
        $sub_kategori_pelayanan->delete();

        Alert::success('Berhasil', 'Sub Pelayanan berhasil dihapus');
        return back();
    }
}
