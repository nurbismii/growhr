<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\Prioritas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PrioritasController extends Controller
{
    public function index()
    {
        $prioritas = Prioritas::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);

        return view('dropdown.prioritas.index', compact('prioritas'))->with('no');
    }

    public function create()
    {
        return view('dropdown.prioritas.create');
    }

    public function store(Request $request)
    {
        Prioritas::create([
            'prioritas' => ucwords($request->prioritas)
        ]);

        Alert::success('Berhasil', 'Prioritas pekerjaan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $prioritas = Prioritas::where('id', $id)->first();

        return view('dropdown.prioritas.edit', compact('prioritas'));
    }

    public function update(Request $request, $id)
    {
        Prioritas::where('id', $id)->update([
            'prioritas' => ucwords($request->prioritas)
        ]);

        Alert::success('Berhasil', 'Prioritas pekerjaan berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        Prioritas::where('id', $id)->delete();

        Alert::success('Berhasil', 'Prioritas pekerjaan berhasil dihapus');
        return back();
    }
}
