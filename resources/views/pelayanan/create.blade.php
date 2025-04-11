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
            <form action="{{ route('laporan-pelayanan.store') }}" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
                            <div class="card-body p-0">
                                <h6 class="card-title text-white fw-bold m-2 text-center">Form Pelayanan</h6>
                            </div>
                        </div>
                        <a href="{{ route('laporan-pelayanan.index') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                        </a>
                    </div>

                    <!-- Informasi Pengaduan -->
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <label for="tanggalPelaporan" class="form-label">Tanggal
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control tanggalPelaporan" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                        </div>
                        <div class="col">
                            <label for="divisiId" class="form-label">Bidang Pelayanan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="divisiId" name="divisi_id" class="form-select select-bidang" required>
                                <option value="">-- Pilih Kategori Pekerjaan --</option>
                                @foreach($divisi as $div)
                                <option value="{{ $div->id }}">{{ $div->divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="kategori_pelayanan" class="form-label">Kategori Pelayanan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="kategori_pelayanan" name="kategori_pelayanan_id" class="form-select select-kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="sub_kategori_pelayanan" class="form-label">Sub Kategori Pelayanan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="sub_kategori_pelayanan" name="sub_kategori_pelayanan_id" class="form-select select-sub-kategori">
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Data Karyawan -->
                <div class="card-body pb-0">
                    <h5 class="text-primary mb-3">Data Karyawan</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nik_karyawan" class="form-label">NIK Karyawan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="nik_karyawan" name="nik_karyawan" class="form-select select-karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach($employee_hris as $emp)
                                <option value="{{ $emp->nik }}">{{ $emp->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control" required readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="departemen" class="form-label">Departemen
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="departemen" name="departemen" class="form-control" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="divisi" class="form-label">Divisi
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="divisi" name="divisi" class="form-control" required readonly>
                        </div>
                    </div>
                </div>

                <div class="card-body pb-0">
                    <h5 class="text-primary mb-3">Waktu Pelayanan</h5>
                    <div class="row g-3">
                        <div class="col">
                            <label for="waktuMulai" class="form-label">Waktu Mulai
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="waktu_mulai" id="waktuMulai" readonly required>
                        </div>
                        <div class="col">
                            <label for="waktuSelesai" class="form-label">Waktu Selesai
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="waktu_selesai" id="waktuSelesai" readonly required>
                        </div>
                        <div class="col">
                            <label for="durasi" class="form-label">Durasi
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="durasi" id="durasi" readonly required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-sm text-white mt-2 btn-mulai" style="white-space: normal; width: 120px;">
                        Mulai <br> Pelayanan
                    </button>
                    <button type="submit" class="btn btn-secondary btn-sm text-white mt-2 btn-selesai text-center" style="white-space: normal; width: 120px;">
                        Selesai<br>Pelayanan
                    </button>

                    <div class="row">
                        <div class="col-6 mt-3">
                            <label for="durasi" class="form-label">Keterangan
                                <span class="text-danger">*</span>
                            </label>
                            <textarea type="text" class="form-control" name="keterangan" required></textarea>
                        </div>

                        <div class="col-6 mt-3">
                            <label class="form-label">Dokumen Laporan

                            </label>
                            <div>
                                <label for="fileInput" class="custom-file-upload">
                                    <i class="bi bi-plus-circle"></i>
                                    <span id="fileLabel" class="ms-2">Pilih file</span>
                                </label>
                                <input type="file" id="fileInput" name="doc_pendukung">
                            </div>
                            <div id="fileName" class="file-name"></div>
                        </div>
                    </div>


                </div>

                <div class="text-end m-3">
                    <button type="submit" class="btn btn-primary text-white">
                        Kirim &nbsp;<span class="tf-icons bx bx-send"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#fileInput").change(function() {
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";
        $("#fileLabel").text(fileName);
        $("#fileName").text(fileName);
    });

    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];

        // Pilih semua elemen dengan class 'tanggalPelaporan' (gunakan class untuk multiple elements)
        document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
            input.value = today;
        });
    });

    $(document).ready(function() {
        $('.select-bidang').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Bidang --",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%' // Menyesuaikan dengan lebar container
        });

        $(window).on('resize', function() {
            $('.select-bidang').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Bidang --",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-kategori').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih kategori --",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan3
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-kategori').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih kategori --",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-sub-kategori').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih sub kategori --",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-sub-kategori').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih sub kategori --",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-karyawan').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Karyawan --",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-karyawan').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih Karyawan --",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        function toggleSelect2($element, disabled) {
            $element.prop('disabled', disabled).trigger('change.select2');
        }

        $('#divisiId').on('change', function() {
            var divisiId = $(this).val();
            var $kategoriSelect = $('#kategori_pelayanan');
            var $subKategoriSelect = $('#sub_kategori_pelayanan');

            $kategoriSelect.html('<option value="">-- Pilih Kategori --</option>');
            $subKategoriSelect.html('<option value="">Pilih Sub Kategori</option>');

            toggleSelect2($kategoriSelect, true);
            toggleSelect2($subKategoriSelect, true);

            if (divisiId) {
                $.ajax({
                    url: '/get-kategori/' + divisiId,
                    type: 'GET',
                    beforeSend: function() {
                        toggleSelect2($kategoriSelect, true);
                    },
                    success: function(data) {
                        $.each(data, function(index, value) {
                            $kategoriSelect.append('<option value="' + value.id + '">' + value.pelayanan + '</option>');
                        });
                    },
                    complete: function() {
                        toggleSelect2($kategoriSelect, false);
                    }
                });
            }
        });

        $('#kategori_pelayanan').on('change', function() {
            var kategoriId = $(this).val();
            var $subKategoriSelect = $('#sub_kategori_pelayanan');

            $subKategoriSelect.html('<option value="">Pilih Sub Kategori</option>');
            toggleSelect2($subKategoriSelect, true);

            if (kategoriId) {
                $.ajax({
                    url: '/get-sub-kategori/' + kategoriId,
                    type: 'GET',
                    beforeSend: function() {
                        toggleSelect2($subKategoriSelect, true);
                    },
                    success: function(data) {
                        $.each(data, function(index, value) {
                            $subKategoriSelect.append('<option value="' + value.id + '">' + value.sub_pelayanan + '</option>');
                        });
                    },
                    complete: function() {
                        toggleSelect2($subKategoriSelect, false);
                    }
                });
            }
        });
    });


    $(document).ready(function() {
        $('#nik_karyawan').change(function() {
            var nik = $(this).val();
            if (nik) {
                $.ajax({
                    url: '/get-karyawan/' + nik,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#nama_karyawan').val(data.nama_karyawan);
                            $('#departemen').val(data.get_divisi.get_departemen.departemen);
                            $('#divisi').val(data.get_divisi.nama_divisi);
                        } else {
                            $('#nama_karyawan').val('');
                            $('#departemen').val('');
                            $('#divisi').val('');
                        }
                    }
                });
            } else {
                $('#nama_karyawan').val('');
                $('#departemen').val('');
                $('#divisi').val('');
            }
        });
    });

    $(document).ready(function() {
        // Fungsi untuk menangkap waktu sekarang dalam format HH:mm:ss
        function getCurrentTime() {
            let now = new Date();
            return now.getHours().toString().padStart(2, '0') + ":" +
                now.getMinutes().toString().padStart(2, '0') + ":" +
                now.getSeconds().toString().padStart(2, '0');
        }

        // Saat tombol "Mulai Pelayanan" diklik
        $(".btn-mulai").click(function() {
            let waktuMulai = getCurrentTime();
            $("#waktuMulai").val(waktuMulai);
        });

        // Saat tombol "Selesai Pelayanan" diklik
        $(".btn-selesai").click(function() {
            let waktuSelesai = getCurrentTime();
            $("#waktuSelesai").val(waktuSelesai);

            // Ambil waktu mulai dan waktu selesai
            let startTime = $("#waktuMulai").val();
            let endTime = waktuSelesai;

            if (startTime) {
                // Konversi waktu ke format Date
                let start = new Date("1970-01-01T" + startTime + "Z");
                let end = new Date("1970-01-01T" + endTime + "Z");

                // Hitung selisih waktu dalam milidetik
                let durationMs = end - start;
                let durationSec = Math.floor(durationMs / 1000); // Konversi ke detik
                let minutes = Math.floor(durationSec / 60);
                let seconds = durationSec % 60;

                // Format durasi menjadi "X Menit Y Detik"
                let durationFormatted = `${minutes} Menit ${seconds} Detik`;

                $("#durasi").val(durationFormatted);
            }
        });
    });
</script>
@endpush

@endsection