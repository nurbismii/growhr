<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\KategoriPekerjaan;
use App\Models\KategoriPelayanan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriPelayananController extends Controller
{
    public function index()
    {
        $kategori_pelayanan = KategoriPelayanan::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.kategori-pelayanan.index', compact('kategori_pelayanan'))->with('no');
    }

    public function create()
    {
        return view('dropdown.kategori-pelayanan.create');
    }

    public function store(Request $request)
    {
        KategoriPelayanan::create([
            'pelayanan' => $request->kategori_pelayanan
        ]);

        Alert::success('Berhasil', 'Pelayanan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $kategori_pelayanan = KategoriPelayanan::findOrFail($id);

        return view('dropdown.kategori-pelayanan.edit', compact('kategori_pelayanan'));
    }

    public function update(Request $request, $id)
    {
        $kategori_pelayanan = KategoriPelayanan::findOrFail($id);

        $kategori_pelayanan->update([
            'pelayanan' => $request->kategori_pelayanan
        ]);

        Alert::success('Berhasil', 'Pelayanan berhasil diperbarui');
        return redirect()->route('kategori-pelayanan.index');
    }

    public function destroy($id)
    {
        $kategori_pelayanan = KategoriPelayanan::findOrFail($id);
        $kategori_pelayanan->delete();

        Alert::success('Berhasil', 'Pelayanan berhasil dihapus');
        return back();
    }
}
