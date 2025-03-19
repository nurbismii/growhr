<?php

namespace App\Http\Controllers\Dropdown;

use App\Http\Controllers\Controller;
use App\Models\StatusPekerjaan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StatuspekerjaanController extends Controller
{
    public function index()
    {
        $status_pekerjaan = StatusPekerjaan::all();
        $title = 'Hapus data!';
        $text = "Kamu yakin ingin menghapus data ini ?";
        confirmDelete($title, $text);


        return view('dropdown.status-pekerjaan.index', compact('status_pekerjaan'))->with('no');
    }

    public function create()
    {
        return view('dropdown.status-pekerjaan.create');
    }

    public function store(Request $request)
    {
        StatusPekerjaan::create([
            'status_pekerjaan' => ucwords($request->status_pekerjaan)
        ]);

        Alert::success('Berhasil', 'Status pekerjaan berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $status = StatusPekerjaan::where('id', $id)->first();

        return view('dropdown.status-pekerjaan.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        StatusPekerjaan::where('id', $id)->update([
            'status_pekerjaan' => ucwords($request->status_pekerjaan)
        ]);

        Alert::success('Berhasil', 'Status pekerjaan berhasil diperbarui');
        return back();
    }

    public function destroy($id)
    {
        StatusPekerjaan::where('id', $id)->delete();

        Alert::success('Berhasil', 'Status pekerjaan berhasil dihapus');
        return back();
    }
}
