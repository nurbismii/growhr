<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanhasilController extends Controller
{
    public function index()
    {
        return view('laporan-hasil.index');
    }

    public function create()
    {
        return view('laporan-hasil.create');
    }
}
