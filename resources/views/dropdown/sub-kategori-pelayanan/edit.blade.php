@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('sub-kategori-pelayanan.update', $sub_kategori_pelayanan->id) }}" method="post">
                    @csrf
                    {{ method_field('patch') }}
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Kategori Pelayanan</button>
                    <a href="{{ route('sub-kategori-pelayanan.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kategoriPelayanan" class="form-label">Nama Pelayanan</label>
                            <Select name="kategori_pelayanan_id" class="form-select" id="kategoriPelayanan">
                                @foreach($kategori_pelayanan as $kp)
                                <option value="{{$kp->id}}">{{ $kp->pelayanan }}</option>
                                @endforeach
                            </Select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subKategoriPelayanan" class="form-label">Nama Sub Pelayanan</label>
                            <input type="text" name="sub_kategori_pelayanan" id="subKategoriPelayanan" class="form-control" value="{{ $sub_kategori_pelayanan->sub_pelayanan }}">
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