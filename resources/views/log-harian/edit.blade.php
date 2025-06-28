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
                <h6 class="card-title text-white fw-bold m-2 text-center">Edit Pekerjaan</h6>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body">
                <form action="{{ route('log-harian.update', $pekerjaan->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalPelaporan" value="{{ date_format($pekerjaan->created_at, 'd/m/Y') }}" readonly>
                            <input type="text" class="form-control" name="pekerjaan_id" value="{{ $pekerjaan->id }}" hidden>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sifatPekerjaan" class="form-label">Sifat Pekerjaan</label>
                            <input type="text" class="form-control" value="{{ $pekerjaan->getSifatPekerjaan->pekerjaan }}" readonly>
                            <input type="hidden" class="form-control" name="sifat_pekerjaan" id="sifatPekerjaan" value="{{ $pekerjaan->getSifatPekerjaan->id }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kategoriKegiatan" class="form-label">Kategori Pekerjaan</label>
                            <select id="kategoriKegiatan" name="kategori_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pekerjaan->getKategoriPekerjaan->id }}">{{ $pekerjaan->getKategoriPekerjaan->kategori_pekerjaan }}</option>
                                @foreach($kategori_pekerjaan as $kategori)
                                @if($kategori->id != $pekerjaan->getKategoriPekerjaan->id)
                                <option value="{{ $kategori->id }}">{{ $kategori->kategori_pekerjaan }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pj_pekerjaan" class="form-label">Penanggung Jawab</label>
                            <select id="pj_pekerjaan" name="pj_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pekerjaan->getPjPekerjaan->id }}">{{ strtoupper($pekerjaan->getPjPekerjaan->name) }}</option>
                                @foreach($user as $u)
                                @if($u->id != $pekerjaan->getPjPekerjaan->id)
                                <option value="{{ $u->id }}">{{ strtoupper($u->name) }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option selected value="{{ $pekerjaan->getPrioritas->id }}">{{ $pekerjaan->getPrioritas->prioritas }}</option>
                                @foreach($prioritas as $priorit)
                                @if($priorit->id != $pekerjaan->getPrioritas->id)
                                <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusPekerjaan" class="form-label">Status Pekerjaan</label>
                            <select id="statusPekerjaan" name="status_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pekerjaan->getStatusPekerjaan->id }}">{{ $pekerjaan->getStatusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sk)
                                @if($sk->id != $pekerjaan->getStatusPekerjaan->id)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control" name="deskripsi_pekerjaan" id="deskripsiTugas" placeholder="Isi Tugas" rows="3" required readonly>{{ $pekerjaan->deskripsi_pekerjaan }}</textarea>
                        </div>

                        @if(Auth::user()->role == 'ASMEN')
                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" value="{{ $pekerjaan->tanggal_mulai }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" value="{{ $pekerjaan->tanggal_selesai }}" required>
                        </div>
                        @else
                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" value="{{ $pekerjaan->tanggal_mulai }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" value="{{ $pekerjaan->tanggal_selesai }}" required readonly>
                        </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Durasi</label>
                            <input type="text" class="form-control duration" name="durasi" id="duration" value="{{ $pekerjaan->durasi }}" required readonly>
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
                                    <span class="slider-value">{{ $pekerjaan->tingkat_kesulitan }}</span>
                                    <input type="range" name="tingkat_kesulitan" class="slider-range" min="1" max="10" step="1" value="{{ $pekerjaan->tingkat_kesulitan }}" required>
                                </div>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="alasanPemilihan" class="form-label">Alasan pemilihan tingkat kesulitan</label>
                            <textarea type="text" rows="2" class="form-control" name="alasan" id="alasanPemilihan" required>{{ $pekerjaan->alasan }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Dokumen (Opsional)</label>
                                <div>
                                    <label for="fileInputEdit{{$pekerjaan->id}}" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="ms-2 fileLabel" id="fileLabelEdit{{$pekerjaan->id}}">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInputEdit{{$pekerjaan->id}}" class="fileInputEdit" name="lampiran">
                                </div>
                                <div class="file-name fileNameEdit{{$pekerjaan->id}}"></div>
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

    // Slider update for all sliders (support edit page with dynamic IDs/classes)
    document.querySelectorAll('.slider-range').forEach(function(slider) {
        const sliderWrapper = slider.closest('.slider-wrapper');
        if (!sliderWrapper) return;
        const sliderValue = sliderWrapper.querySelector('.slider-value');
        function updateValuePosition() {
            const min = parseInt(slider.min, 10);
            const max = parseInt(slider.max, 10);
            const val = parseInt(slider.value, 10);
            const percent = ((val - min) / (max - min)) * 100;
            sliderValue.style.left = `calc(${percent}% - 5px)`;
            sliderValue.textContent = slider.value;
        }
        slider.addEventListener('input', updateValuePosition);
        updateValuePosition();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@endsection