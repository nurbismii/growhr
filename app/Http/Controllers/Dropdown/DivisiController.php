<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.divisi.index', compact('divisi'))->with('no');
    }

    public function create()
    {
        return view('dropdown.divisi.create');
    }

    public function store(Request $request)
    {
        Divisi::create([
            'divisi' => ucwords($request->divisi),
        ]);

        Alert::success('Berhasil', 'Divisi berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $div = Divisi::where('id', $id)->first();

        return view('dropdown.divisi.edit', compact('div'));
    }

    public function update(Request $request, $id)
    {
        Divisi::where('id', $id)->update([
            'divisi' => ucwords($request->divisi),
        ]);

        Alert::success('Berhasil', 'Divisi berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        Divisi::where('id', $id)->delete();

        Alert::success('Berhasil', 'Divisi berhasil dihapus');
        return back();
    }
}
