@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('status-pekerjaan.store') }}" method="post">
                    @csrf
                    <button class="btn btn-primary btn-lg mb-4">Form Status Pekerjaan</button>
                    <a href="{{ route('status-pekerjaan.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Status Pekerjaan</label>
                            <input type="text" name="status_pekerjaan" class="form-control">
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