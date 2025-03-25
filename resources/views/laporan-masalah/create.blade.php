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
        border-color: #8c52ff;
    }

    .custom-file-upload i {
        font-size: 24px;
        color: #8c52ff;
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
                <form action="{{ route('laporan-masalah.store') }}" method="post" enctype="multipart/form-data">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
                            <div class="card-body p-0">
                                <h6 class="card-title text-white fw-bold m-2 text-center">Form Pengaduan</h6>
                            </div>
                        </div>

                        <a href="{{ route('laporan-masalah.index') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jenisKegiatan" class="form-label">Jenis Pekerjaan</label>
                            <select id="jenisKegiatan" name="jenis_pekerjaan_id" class="form-select select-pekerjaan" required>
                                <option value="">-- Pilih Kategori Pekerjaan --</option>
                                @foreach($pekerjaan as $pk)
                                <option value="{{ $pk->id }}">{{ $pk->deskripsi_pekerjaan }} | {{ $pk->tanggal_mulai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="divisi" class="form-label">Kategori Kendala</label>
                            <select id="divisi" name="kategori_kendala" class="form-select" required>
                                <option value="">-- Pilih Kategori Kendala --</option>
                                <option value="Man">Man (Manusia)</option>
                                <option value="Method">Metode</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Tingkat Dampak</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option value="">-- Pilih Prioritas --</option>
                                @foreach($prioritas as $prioriti)
                                <option value="{{ $prioriti->id }}">{{ $prioriti->prioritas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusKegiatan" class="form-label">Status Penyelesaian</label>
                            <select id="statusKegiatan" name="status_pekerjaan_id" class="form-select" required>
                                <option value="">-- Pilih Status Pekerjaan --</option>
                                @foreach($status_pekerjaan as $sk)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="alasanPenentuanTingkatDampak" class="form-label">Alasan Penentuan Tingkat Dampak</label>
                            <input class="form-control" name="alasan_tingkat_dampak_pengaduan" id="alasanPenentuanTingkatDampak" required></input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Kendala</label>
                            <textarea class="form-control" name="deskripsi_kendala" id="deskripsiTugas" placeholder="Isi deksripsi" rows="2" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="langkahPenyelesaian" class="form-label">Langkah Penyelesaian</label>
                            <textarea class="form-control" name="langkah_penyelesaian" id="langkahPenyelesaian" placeholder="Langkah penyelesaian" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Dokumen Bukti Permasalahan</label>
                                <div>
                                    <label for="InputPermasalahan" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span id="labelPermasalahan" class="ms-2">Pilih file</span>
                                    </label>
                                    <input type="file" id="InputPermasalahan" name="doc_permasalahan">
                                </div>
                                <div id="DocPermasalahan" class="file-name"></div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Dokumen Analisis Risiko</label>
                                <div>
                                    <label for="InputAnalisa" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span id="labelAnalisa" class="ms-2">Pilih file</span>
                                    </label>
                                    <input type="file" id="InputAnalisa" name="doc_analisa">
                                </div>
                                <div id="DocAnalisa" class="file-name"></div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Dokumen Solusi (Opsional)</label>
                                <div>
                                    <label for="InputSolusi" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span id="labelSolusi" class="ms-2">Pilih file</span>
                                    </label>
                                    <input type="file" id="InputSolusi" name="doc_solusi">
                                </div>
                                <div id="DocSolusi" class="file-name"></div>
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
    $(document).ready(function() {
        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById("tanggalPelaporan").value = today;
    });
</script>

<script>
    $("#InputPermasalahan").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#labelPermasalahan").text(fileName);
        $("#DocPermasalahan").text(fileName);
    });

    $("#InputAnalisa").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#labelAnalisa").text(fileName);
        $("#DocAnalisa").text(fileName);
    });

    $("#InputSolusi").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#labelSolusi").text(fileName);
        $("#DocSolusi").text(fileName);
    });
</script>
@endpush

@endsection