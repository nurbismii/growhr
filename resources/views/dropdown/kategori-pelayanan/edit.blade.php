@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('kategori-pelayanan.update', $kategori_pelayanan->id) }}" method="post">
                    @csrf
                    {{ method_field('patch') }}
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Kategori Pelayanan</button>
                    <a href="{{ route('kategori-pelayanan.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="divisiId" class="form-label">Bidang</label>
                            <select name="divisi_id" class="form-select" id="divisiId">
                                <option value="{{ $kategori_pelayanan->divisi_id }}" selected>{{ $kategori_pelayanan->divisi ? $kategori_pelayanan->divisi->divisi : '' }}</option>
                                @foreach($bidang as $bidang)
                                <option value="{{ $bidang->id }}">{{ $bidang->divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategoriPelayanan" class="form-label">Kategori Pelayanan</label>
                            <input type="text" name="kategori_pelayanan" value="{{ $kategori_pelayanan->pelayanan }}" class="form-control">
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