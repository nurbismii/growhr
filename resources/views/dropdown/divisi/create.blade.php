@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('divisi.store') }}" method="post">
                    @csrf
                    <button class="btn btn-primary btn-lg mb-4">Form Divisi</button>
                    <a href="{{ route('divisi.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Divisi</label>
                            <input type="text" name="divisi" class="form-control">
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