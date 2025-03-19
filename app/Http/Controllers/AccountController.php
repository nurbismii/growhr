<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{
    public function index()
    {
        $user = User::with('getEmployee.getDivisi.getDepartemen')->where('id', Auth::user()->id)->first();

        return view('account.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $profileImage = null;

        if ($image = $request->file('image')) {
            $path = 'img/profile';
            $profileImage = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->move($path, $profileImage);
        }

        User::where('id', $id)->update([
            'name' => $request->name,
            'nama_panggilan' => $request->nama_panggilan,
            'tempat_lahir' => $request->tempat_lahir,
            'image' => $profileImage
        ]);
        Alert::success('Berhasil', 'Profile kamu berhasil diperbarui.');
        return back();
    }
}
