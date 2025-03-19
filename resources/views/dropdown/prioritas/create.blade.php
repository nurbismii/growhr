@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-8">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('prioritas.store') }}" method="post">
                    @csrf
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Prioritas</button>
                    <a href="{{ route('prioritas.index') }}" class="btn btn-primary text-white float-end">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                    </a>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <input type="text" name="prioritas" id="prioritas" class="form-control">
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