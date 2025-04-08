@extends('layouts.app')

@section('content')

@push('styles')
<style>
    .custom-file-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ccc;
        border-radius: 5px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        width: 100%;
        height: 60px;
        position: relative;
    }

    .custom-file-upload:hover {
        border-color: #696cff;
    }

    .custom-file-upload i {
        font-size: 24px;
        color: #696cff;
    }

    .file-name {
        margin-top: 10px;
        font-size: 14px;
        color: #333;
    }

    input[type="file"] {
        display: none;
    }
</style>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
                        <div class="card-body p-0">
                            <h6 class="card-title text-white fw-bold m-2 text-center">Laporan Hasil</h6>
                        </div>
                    </div>
                    <a href="{{ route('laporan-hasil.index') }}" class="btn btn-primary">
                        <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Hasil
                    </a>
                </div>

                <form action="{{ route('laporan-hasil.store') }}" method="post" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jenisKegiatan" class="form-label">Deskripsi Pekerjaan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="jenisKegiatan" name="pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Deskripsi Pekerjaan --</option>
                                @foreach($pekerjaan as $pk)
                                <option value="{{ $pk->id }}">{{ $pk->deskripsi_pekerjaan }} | {{ $pk->tanggal_mulai }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="statusLaporan" class="form-label">Status Laporan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="statusLaporan" name="status_laporan" class="form-select" id="statusLaporan" required>
                                <option value="">-- Pilih Status Laporan --</option>
                                <option value="diajukan">Diajukan</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="revisi">Revisi</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label">Keterangan
                                <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="keterangan" value="{{ old('keterangan') }}">
                            <div id="editor" style="min-height: 160px;">{!! old('keterangan') !!}</div>
                        </div>

                        <div class="col-md-12 mb-3 mt-5">
                            <div class="mb-3">
                                <label class="form-label">Dokumen Laporan
                                    <span class="text-danger">*</span>
                                </label>
                                <div>
                                    <label for="fileInput" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span id="fileLabel" class="ms-2">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInput" name="doc_laporan">
                                </div>
                                <div id="fileName" class="file-name"></div>
                            </div>
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

@push('script')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById("tanggalPelaporan").value = today;
    });

    document.getElementById("uploadForm").addEventListener("submit", function(event) {
        let fileInput = document.getElementById("fileInput");
        let errorMessage = '';

        // Cek apakah file tidak dipilih
        if (!fileInput.files.length) {
            errorMessage = 'Harap pilih dokumen laporan!';
        }

        // Jika ada error, munculkan SweetAlert dan hentikan form submission
        if (errorMessage) {
            event.preventDefault(); // Mencegah form dikirim
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: errorMessage,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

<script>
    $("#fileInput").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#fileLabel").text(fileName);
        $("#fileName").text(fileName);
    });
</script>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
    quill.on('text-change', function(delta, oldDelta, source) {
        document.querySelector("input[name='keterangan']").value = quill.root.innerHTML;
    })
</script>
@endpush

@endsection