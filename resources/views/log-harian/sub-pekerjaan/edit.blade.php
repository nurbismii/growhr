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

    .slider-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input[type="range"] {
        width: 100%;
        accent-color: #8c52ff;
    }

    .slider-wrapper {
        position: relative;
        width: 100%;
    }

    .slider-value {
        position: absolute;
        top: 30px;
        /* Pindah angka ke bawah */
        left: 50%;
        transform: translateX(-50%);
        font-weight: bold;
        color: #55667a;
        background: white;
        padding: 2px 6px;
        border-radius: 5px;
        font-size: 14px;
    }
</style>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-2 py-1" style="max-width: 26rem; height: 2.5rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Edit sub pekerjaan</h6>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('log-harian.update.sub', $subpk->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalPelaporan" value="{{ date_format($subpk->created_at, 'd/m/Y') }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sifatPekerjaan" class="form-label">Sifat Pekerjaan</label>
                            <input type="text" class="form-control" value="{{ $subpk->getSifatPekerjaan->pekerjaan }}" readonly>
                            <input type="hidden" class="form-control" name="sifat_pekerjaan" value="{{ $subpk->getSifatPekerjaan->id }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kategoriKegiatan" class="form-label">Kategori Pekerjaan</label>
                            <select id="kategoriKegiatan" name="kategori_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $subpk->getKategoriPekerjaan->id }}">{{ $subpk->getKategoriPekerjaan->kategori_pekerjaan }}</option>
                                @foreach($kategori_pekerjaan as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->kategori_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pj_pekerjaan" class="form-label">Penanggung Jawab</label>
                            <select id="pj_pekerjaan" name="pj_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $subpk->getPjPekerjaan->id }}">{{ $subpk->getPjPekerjaan->name }}</option>
                                @foreach($user as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option selected value="{{ $subpk->getPrioritas->id }}">{{ $subpk->getPrioritas->prioritas }}</option>
                                @foreach($prioritas as $priorit)
                                <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusPekerjaan" class="form-label">Status Pekerjaan</label>
                            <select id="statusPekerjaan" name="status_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $subpk->getStatusPekerjaan->id }}">{{ $subpk->getStatusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sk)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control" name="deskripsi_pekerjaan" id="deskripsiTugas" placeholder="Isi Tugas" rows="3" required readonly>{{ $subpk->pekerjaanHasOne->deskripsi_pekerjaan }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="subDeskripsiPekerjaan" class="form-label">Sub Deskripsi Tugas</label>
                            <textarea class="form-control" name="sub_deskripsi_pekerjaan" id="subDeskripsiPekerjaan" placeholder="Isi Sub Deskripsi Tugas" rows="3" required>{{ $subpk->sub_deskripsi_pekerjaan }}</textarea>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" value="{{ $subpk->tanggal_mulai }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" value="{{ $subpk->tanggal_selesai }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Durasi</label>
                            <input type="text" class="form-control duration" name="durasi" id="duration" value="{{ $subpk->durasi }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="text" class="form-control deadline" name="deadline" id="deadline" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-secondary">
                                Tingkat Kesulitan <span class="text-danger">*</span>
                            </label>
                            <div class="slider-container">
                                <span>1</span>
                                <div class="slider-wrapper">
                                    <span class="slider-value" id="sliderValue">{{ $subpk->tingkat_kesulitan }}</span>
                                    <input type="range" name="tingkat_kesulitan" class="slider-range" id="difficultySlider" min="1" max="10" step="1" value="{{ $subpk->tingkat_kesulitan }}" required>
                                </div>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="alasanPemilihan" class="form-label">Alasan pemilihan tingkat kesulitan</label>
                            <textarea type="text" rows="2" class="form-control" name="alasan" id="alasanPemilihan" required>{{$subpk->alasan}}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Dokumen (Opsional)</label>
                                <div>
                                    <label for="fileInputSubEdit{{$subpk->id}}" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="ms-2 fileLabel" id="fileLabelSubEdit{{$subpk->id}}">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInputSubEdit{{$subpk->id}}" class="fileInputSubEdit" name="lampiran">
                                </div>
                                <div class="file-name fileNameSubEdit{{$subpk->id}}"></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white float-end me-2">
                        Kirim &nbsp;<span class="tf-icons bx bx-send"></span>
                    </button>
                    <a href="{{ route('log-harian.index') }}" class="btn btn-secondary float-end me-2">
                        <span class="tf-icons"></span> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const start = document.getElementById("tanggalMulai").value;
        const end = document.getElementById("tanggalSelesai").value;

        if (start && end) {
            calculateDays();
        }

        // Event listener biar realtime saat input berubah
        document.getElementById("tanggalMulai").addEventListener("change", calculateDays);
        document.getElementById("tanggalSelesai").addEventListener("change", calculateDays);
    });

    function calculateDays() {
        const startDate = new Date(document.getElementById("tanggalMulai").value);
        const endDate = new Date(document.getElementById("tanggalSelesai").value);
        const duration = document.getElementById("duration");
        const deadline = document.getElementById("deadline");

        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset waktu agar lebih akurat

        if (!isNaN(startDate) && !isNaN(endDate)) {
            if (endDate < startDate) {
                Swal.fire({
                    icon: "error",
                    title: "Tanggal Tidak Valid!",
                    text: "Tanggal Selesai tidak boleh lebih kecil dari Tanggal Mulai.",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                });
                document.getElementById("tanggalSelesai").value = ""; // Reset input
                document.getElementById("duration").value = "";
                document.getElementById("deadline").value = "";
                return;
            }
            const timeDiff = endDate - startDate;

            tglAkhir = endDate
            tglAkhir.setHours(0, 0, 0, 0)

            let selisih = ((tglAkhir - today) / (1000 * 60 * 60 * 24));

            if (selisih < 0) {
                selisih = 'H+' + String(selisih).replace('-', '');
            } else {
                selisih = 'H-' + String(selisih).replace('-', '');
            }

            const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
            document.getElementById("duration").value = daysDiff + " Hari";
            document.getElementById("deadline").value = selisih;
        } else {
            document.getElementById("duration").value = "";
        }
    }

    document.getElementById("tanggalMulai").addEventListener("change", calculateDays);
    document.getElementById("tanggalSelesai").addEventListener("change", calculateDays);

    $("#fileInput").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#fileLabel").text(fileName);
        $("#fileName").text(fileName);
    });

    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById("tanggalPelaporan").value = today;
    });

    const slider = document.getElementById("difficultySlider");
    const sliderValue = document.getElementById("sliderValue");

    function updateValuePosition() {
        const percent = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
        sliderValue.style.left = `calc(${percent}% - 5px)`;
        sliderValue.textContent = slider.value;
    }

    if (slider && sliderValue) {
        slider.addEventListener("input", updateValuePosition);
        updateValuePosition();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@endsection