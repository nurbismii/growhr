<?php

namespace App\Http\Controllers;

use App\Models\Hris\EmployeeHris;
use App\Models\User;
use Illuminate\Http\Request;
use Alert;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('nik', '!=', null)->get();

        return view('user.index', compact('user'))->with('no');
    }

    public function create()
    {
        return view('user.create');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('user.edit', compact('user'));
    }


    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return back();
    }

    public function daftar(Request $request)
    {
        $check = EmployeeHris::where('nik', $request->nik)->first();

        if ($check) {
            if ($request->password === $request->password_confirmation) {
                User::create([
                    'name' => ucwords($request->name),
                    'nik' => $request->nik,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
                Alert::success('Berhasil', 'Silahkan login menggunakan email yang telah terdaftar');
                return redirect()->route('login');
            }
            Alert::error('Gagal', 'Konfirmasi password tidak sesuai dengan password kamu.');
            return back();
        }
        Alert::error('Gagal', 'NIK kamu tidak ditemukan pada sistem kami, laporankan kendala ini ke HR');
        return back();
    }
}
