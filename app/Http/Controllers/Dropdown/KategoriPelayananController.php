<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\KategoriPekerjaan;
use App\Models\KategoriPelayanan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriPelayananController extends Controller
{
    public function index()
    {
        $kategori_pelayanan = KategoriPelayanan::with('divisi')->orderBy('pelayanan', 'asc')->get();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.kategori-pelayanan.index', compact('kategori_pelayanan'))->with('no');
    }

    public function create()
    {
        $bidang = Divisi::orderBy('id', 'asc')->get();

        return view('dropdown.kategori-pelayanan.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        KategoriPelayanan::create([
            'divisi_id' => $request->divisi_id,
            'pelayanan' => $request->kategori_pelayanan
        ]);

        Alert::success('Berhasil', 'Pelayanan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $kategori_pelayanan = KategoriPelayanan::with('divisi')->findOrFail($id);
        $bidang = Divisi::orderBy('id', 'asc')->get();

        return view('dropdown.kategori-pelayanan.edit', compact('kategori_pelayanan', 'bidang'));
    }

    public function update(Request $request, $id)
    {
        $kategori_pelayanan = KategoriPelayanan::findOrFail($id);

        $kategori_pelayanan->update([
            'divisi_id' => $request->divisi_id,
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
