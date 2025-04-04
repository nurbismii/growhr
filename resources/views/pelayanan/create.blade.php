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
                        <a href="{{ route('laporan-masalah.index') }}" class="btn btn-primary">
                            <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali
                        </a>
                    </div>

                    <!-- Informasi Pengaduan -->
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="date" class="form-control tanggalPelaporan" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                        </div>
                        <div class="col">
                            <label for="divisiId" class="form-label">Bidang Pelayanan</label>
                            <select id="divisiId" name="divisi_id" class="form-select select-bidang" required>
                                <option value="">-- Pilih Kategori Pekerjaan --</option>
                                @foreach($divisi as $div)
                                <option value="{{ $div->id }}">{{ $div->divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="kategori_pelayanan" class="form-label">Kategori Pelayanan</label>
                            <select id="kategori_pelayanan" name="kategori_pelayanan_id" class="form-select select-kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori_pelayanan as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->pelayanan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="sub_kategori_pelayanan" class="form-label">Sub Kategori Pelayanan</label>
                            <select id="sub_kategori_pelayanan" name="sub_kategori_pelayanan_id" class="form-select select-sub-kategori" required>
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
                            <label for="nik_karyawan" class="form-label">NIK Karyawan</label>
                            <select id="nik_karyawan" name="nik_karyawan" class="form-select select-karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach($employee_hris as $emp)
                                <option value="{{ $emp->nik }}">{{ $emp->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control" required readonly>
                        </div>
                    </div>
                </div>

                <div class="card-body pb-0">
                    <h5 class="text-primary mb-3">Waktu Pelayanan</h5>
                    <div class="row g-3">
                        <div class="col">
                            <label for="waktuMulai" class="form-label">Waktu Mulai</label>
                            <input type="text" class="form-control" name="waktu_mulai" id="waktuMulai" readonly required>
                        </div>
                        <div class="col">
                            <label for="waktuSelesai" class="form-label">Waktu Selesai</label>
                            <input type="text" class="form-control" name="waktu_selesai" id="waktuSelesai" readonly required>
                        </div>
                        <div class="col">
                            <label for="durasi" class="form-label">Durasi</label>
                            <input type="text" class="form-control" name="durasi" id="durasi" readonly required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-sm text-white mt-2 btn-mulai">
                        Mulai </br> Pelayanan
                    </button>
                    <button type="submit" class="btn btn-secondary btn-sm  btn text-white mt-2 btn-selesai">
                        Selesai </br> Pelayanan
                    </button>

                    <div class="col mt-3">
                        <label for="durasi" class="form-label">Keterangan</label>
                        <textarea type="text" class="form-control" name="keterangan" required></textarea>
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
            placeholder: "Bidang",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%' // Menyesuaikan dengan lebar container
        });

        $(window).on('resize', function() {
            $('.select-bidang').select2({
                theme: 'bootstrap-5',
                placeholder: "Bidang",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-kategori').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih kategori",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan3
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-kategori').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih kategori",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-sub-kategori').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih sub kategori",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-sub-kategori').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih sub kategori",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('.select-karyawan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Karyawan",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $(window).on('resize', function() {
            $('.select-karyawan').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Karyawan",
                allowClear: true,
                width: '100%'
            });
        });
    });

    $(document).ready(function() {
        $('#kategori_pelayanan').on('change', function() {
            var kategoriID = $(this).val();
            if (kategoriID) {
                $.ajax({
                    url: '{{ route("get.subkategori") }}',
                    type: 'POST',
                    data: {
                        kategori_pelayanan_id: kategoriID,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#sub_kategori_pelayanan').empty().append('<option value="">Pilih Sub Kategori</option>');
                        $.each(data, function(key, value) {
                            $('#sub_kategori_pelayanan').append('<option value="' + value.id + '">' + value.sub_pelayanan + '</option>');
                        });
                    }
                });
            } else {
                $('#sub_kategori_pelayanan').empty().append('<option value="">Pilih Sub Kategori</option>');
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
                            $('#nama_karyawan').val(data.nama_karyawan); // Sesuaikan field "nama"
                        } else {
                            $('#nama_karyawan').val('');
                        }
                    }
                });
            } else {
                $('#nama_karyawan').val('');
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