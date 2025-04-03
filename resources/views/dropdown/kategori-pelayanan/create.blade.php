@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('kategori-pelayanan.store') }}" method="post">
                    @csrf
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Kategori Pelayanan</button>
                    <a href="{{ route('kategori-pelayanan.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategoriPelayanan" class="form-label">Nama Pelayanan</label>
                            <input type="text" name="kategori_pelayanan" id="kategoriPelayanan" class="form-control">
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