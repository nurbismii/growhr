@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-1 mb-3"><span class="text-primary fw-light">Detail Profile</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <form id="formAccountSettings" method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                    <h5 class="card-header text-s ">Detail Profile</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ asset('img/profile/' . $user->image )}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <i class="tf-icons bx bx-upload"></i>
                                    <input type="file" id="upload" name="image" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />

                    @csrf
                    {{ method_field('patch') }}
                    <h5 class="card-header text-primary">Identitas Pribadi</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="namaLengkap" name="name" value="{{ $user->name }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="namaPanggilan" class="form-label">Nama Panggilan</label>
                                <input class="form-control" type="text" id="namaPanggilan" name="nama_panggilan" value="{{ $user->nama_panggilan }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                                <input class="form-control" type="text" id="jenisKelamin" value="{{ optional($user->getEmployee)->jenis_kelamin ? 'Laki-Laki' : 'Perempuan' }}" disabled />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="tempatLahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir" id="tempatLahir" value="{{ $user->tempat_lahir ?? '-' }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="tanggalLahir" value="{{ optional($user->getEmployee)->tgl_lahir }}" disabled />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="usia" class="form-label">Usia</label>
                                <input type="text" class="form-control" id="usia" readonly />
                            </div>
                        </div>
                    </div>
                    <h5 class="card-header text-primary">Data Karyawan</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="organization" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="organization" value="{{ $user->nik }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="organization" class="form-label">Departemen</label>
                                <input type="text" class="form-control" id="organization" value="{{ $user->getEmployee ? $user->getEmployee->getDivisi->getDepartemen->departemen : '-' }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="address" class="form-label">Divisi</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $user->getEmployee ? $user->getEmployee->getDivisi->nama_divisi : '-' }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="organization" class="form-label">Area Kerja</label>
                                <input type="text" class="form-control" id="organization" value="{{ $user->getEmployee ? $user->getEmployee->area_kerja : '-' }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="organization" class="form-label">Posisi</label>
                                <input type="text" class="form-control" id="organization" value="{{ $user->getEmployee ? $user->getEmployee->posisi : '-' }}" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="address" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="address" value="{{ $user->getEmployee ? $user->getEmployee->jabatan : '-' }}" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary text-white float-end mb-3">
                                <span class="tf-icons bx bx-edit"></span>&nbsp; Edit
                            </button>
                        </div>
                    </div>
                </form>
                <!-- /Account -->
            </div>
        </div>
    </div>
</div>
@endsection