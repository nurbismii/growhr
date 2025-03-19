<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\KategoriPekerjaan;
use App\Models\Pekerjaan;
use App\Models\Prioritas;
use App\Models\StatusPekerjaan;
use Illuminate\Http\Request;

class LaporanmasalahController extends Controller
{
    public function index()
    {
        return view('laporan-masalah.index');
    }

    public function create()
    {
        return view('laporan-masalah.create');
    }

    public function store(Request $request) {}
}
