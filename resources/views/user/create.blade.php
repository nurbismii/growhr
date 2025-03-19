@extends('layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-6">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Pengguna</button>
                    <a href="{{ route('laporan-hasil.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">Nomor Induk Karyawan</label>
                            <input type="text" class="form-control" name="nik" id="nik">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white float-end">
                        Kirim &nbsp;<span class="tf-icons bx bx-send"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection