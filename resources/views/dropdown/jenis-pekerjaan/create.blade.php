@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('jenis-pekerjaan.store') }}" method="post">
                    @csrf
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Jenis Pekerjaan</button>
                    <a href="{{ route('jenis-pekerjaan.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="jenisPekerjaan" class="form-label">Kategori Pekerjaan</label>
                            <input type="text" name="jenis_pekerjaan" class="form-control">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="deskripsiPekerjaan" class="form-label">Deskripsi Pekerjaan</label>
                            <textarea name="deskripsi_pekerjaan" class="form-control" rows="3" id=""></textarea>
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