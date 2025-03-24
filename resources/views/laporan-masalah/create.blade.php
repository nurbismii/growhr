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
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body">
                <form action="" method="post">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="card text-white bg-primary shadow-lg px-4 py-2" style="max-width: 22rem; height: 3.5rem;">
                            <div class="card-body p-0">
                                <h6 class="card-title text-white fw-bold m-2 text-center">Form Kegiatan</h6>
                            </div>
                        </div>

                        <a href="{{ route('laporan-masalah.index') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Kegiatan
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jenisKegiatan" class="form-label">Jenis Pekerjaan</label>
                            <select id="jenisKegiatan" name="jenis_pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Kategori Pekerjaan --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="divisi" class="form-label">Kategori Kendala</label>
                            <select id="divisi" name="divisi_id" class="form-select" required>
                                <option selected disabled>-- Pilih Divisi --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Tingkat Dampak</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option selected disabled>-- Pilih Prioritas --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusKegiatan" class="form-label">Status Penyelesaian</label>
                            <select id="statusKegiatan" name="status_pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Status Pekerjaan --</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Kendala</label>
                            <textarea class="form-control" name="deskripsi_tugas" id="deskripsiTugas" placeholder="Isi Tugas" rows="2" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Solusi yang telah dicoba (Opsional)</label>
                            <textarea class="form-control" name="deskripsi_tugas" id="deskripsiTugas" placeholder="Isi Tugas" rows="2" required></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Perlu ditindaklanjuti atasan ?</label>
                            <br>
                            <input type="radio" class="btn-check" name="options" id="option1">
                            <label class="btn btn-outline-primary btn-sm" for="option1">Ya</label>

                            <input type="radio" class="btn-check" name="options" id="option2">
                            <label class="btn btn-outline-primary btn-sm" for="option2">Tidak</label>
                        </div>


                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Dokumen (Opsional)</label>
                                <div>
                                    <label for="fileInput" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span id="fileLabel" class="ms-2">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInput" name="lampiran">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById("tanggalPelaporan").value = today;
    });
</script>

<script>
    $("#fileInput").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#fileLabel").text(fileName);
        $("#fileName").text(fileName);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"></script>
@endpush

@endsection