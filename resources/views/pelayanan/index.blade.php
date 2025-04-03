@extends('layouts.app')

@section('content')

@push('styles')
<style>
    .tooltip-inner {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }

    .tooltip.bs-tooltip-top .tooltip-arrow::before {
        border-top-color: var(--bs-primary) !important;
    }

    .tooltip.bs-tooltip-bottom .tooltip-arrow::before {
        border-bottom-color: var(--bs-primary) !important;
    }

    .tooltip.bs-tooltip-start .tooltip-arrow::before {
        border-left-color: var(--bs-primary) !important;
    }

    .tooltip.bs-tooltip-end .tooltip-arrow::before {
        border-right-color: var(--bs-primary) !important;
    }

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

    .btn-light-gray {
        background-color: #8c52ff;
        /* Light gray color */
        color: #fff;
        /* Text color */
    }

    .dataTables_wrapper .dataTables_filter {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1000;
        padding: 10px 0;
    }

    .dataTables_wrapper .dataTables_paginate {
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 1000;
        padding: 10px 0;
    }

    div.dt-scroll-body {
        border-bottom-color: transparent !important;
    }

    .sub-row td {
        padding: 0.625rem 1.25rem;
        padding-top: 0.625rem;
        padding-right: 1.25rem;
        padding-bottom: 0.625rem;
        padding-left: 1.25rem;
    }

    #laporan-masalah {
        table-layout: fixed;
        width: 100%;
    }

    #laporan-masalah tbody tr {
        background: none !important;
    }

    #laporan-masalah td:first-child,
    #laporan-masalah th:first-child {
        width: 50px;
        /* Atur lebar yang sama */
        text-align: center;
    }

    #laporan-masalah th,
    #laporan-masalah td {
        text-align: center;
        white-space: nowrap;
    }
</style>
@endpush
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Laporan Pelayanan</h6>
            </div>
        </div>
        <a href="{{ route('laporan-pelayanan.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Pelayanan
        </a>
    </div>

    <form id="search-form">
        <div class="row g-2 d-flex flex-wrap mb-3">
            @csrf
            <div class="col-12 col-sm-6 col-md-3">
                <select id="divisiId-f" name="divisi_id[]" class="form-control select-bidang">
                    <option value="" disabled selected>Bidang</option>
                    @foreach($divisi as $div)
                    <option value="{{ $div->id }}">{{ $div->divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="kategori_pelayanan_id[]" class="form-control select-kategori w-100">
                    <option value="" disabled selected>Kategori</option>
                    @foreach($kategori_pelayanan as $kp)
                    <option value="{{ $kp->id }}">{{ $kp->pelayanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="pic[]" class="form-control select-pic w-100">
                    <option value="" disabled selected>Person in Charge</option>
                    @foreach($user as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group w-100">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" name="tanggal" class="form-control daterange" />
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table" id="laporan-pelayanan">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center text-white">No</th>
                        <th class="text-center text-white">Tanggal Pelaporan</th>
                        <th class="text-center text-white">Bidang Pelayanan</th>
                        <th class="text-center text-white">Kategori Pelayanan</th>
                        <th class="text-center text-white">Sub Kategori Pelayanan</th>
                        <th class="text-center text-white">Waktu Mulai</th>
                        <th class="text-center text-white">Waktu Selesai</th>
                        <th class="text-center text-white">Durasi Pelayanan</th>
                        <th class="text-center text-white">Keterangan</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelayanan as $layanan)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ date('d-m-Y', strtotime($layanan->created_at)) }}</td>
                        <td>{{ $layanan->divisi->divisi }}</td>
                        <td>{{ $layanan->kategoriPelayanan->pelayanan }}</td>
                        <td>{{ $layanan->subKategoriPelayanan->sub_pelayanan }}</td>
                        <td>{{ $layanan->waktu_mulai }}</td>
                        <td>{{ $layanan->waktu_selesai }}</td>
                        <td>{{ $layanan->durasi }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $layanan->keterangan }}">
                                {{ substr($layanan->keterangan, 0, 50) }}....
                            </span>
                        </td>
                        <td>{{ $layanan->pic->name }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit text-primary"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit{{$layanan->id}}"><i class="bx text-primary bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('laporan-pelayanan.destroy', $layanan->id) }}" data-confirm-delete="true"><i class="bx text-primary bx-trash me-2"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($pelayanan as $layanan)
<div class="modal fade" id="edit{{$layanan->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Laporan Masalah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan-pelayanan.update', $layanan->id) }}" method="post" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="card-body pb-0">
                        <!-- Informasi Pengaduan -->
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div class="col">
                                <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                                <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($layanan->created_at)) }}" readonly>
                            </div>
                            <div class="col">
                                <label for="divisiIdModal" class="form-label">Bidang Pelayanan</label>
                                <select id="divisiIdModal" name="divisi_id" class="form-select select-bidang-modal" required>
                                    <option value="{{ $layanan->divisi_id }}">{{ $layanan->divisi->divisi }}</option>
                                    @foreach($divisi_modal as $div_m)
                                    <option value="{{ $div_m->id }}">{{ $div_m->divisi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="kategoriPelayanan" class="form-label">Kategori Pelayanan</label>
                                <select id="kategoriPelayanan" name="kategori_pelayanan_id" class="form-select select-kategori-modal" required>
                                    <option value="{{ $layanan->kategori_pelayanan_id }}">{{$layanan->kategoriPelayanan->pelayanan}}</option>
                                    @foreach($kategori_pelayanan as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->pelayanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="subKategoriPelayanan" class="form-label">Sub Kategori Pelayanan</label>
                                <select id="subKategoriPelayanan" name="sub_kategori_pelayanan_id" class="form-select select-sub-kategori-modal" required>
                                    <option value="{{ $layanan->sub_kategori_pelayanan_id }}">{{ $layanan->subKategoriPelayanan->sub_pelayanan }}</option>
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
                                <select id="nik_karyawan" name="nik_karyawan" class="form-select select-karyawan-modal" required>
                                    <option value="{{ $layanan->nik_karyawan }}">{{ $layanan->nik_karyawan }}</option>
                                    @foreach($employee_hris as $emp)
                                    <option value="{{ $emp->nik }}">{{ $emp->nik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control nama-karyawan-modal" value="{{ $layanan->nama_karyawan }}" required readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pb-0">
                        <h5 class="text-primary mb-3">Waktu Pelayanan</h5>
                        <div class="row g-3">
                            <div class="col">
                                <label for="waktuMulai" class="form-label">Waktu Mulai</label>
                                <input type="text" class="form-control" name="waktu_mulai" id="waktuMulai" value="{{ $layanan->waktu_mulai }}" readonly required>
                            </div>
                            <div class="col">
                                <label for="waktuSelesai" class="form-label">Waktu Selesai</label>
                                <input type="text" class="form-control" name="waktu_selesai" id="waktuSelesai" value="{{ $layanan->waktu_selesai }}" readonly required>
                            </div>
                            <div class="col">
                                <label for="durasi" class="form-label">Durasi</label>
                                <input type="text" class="form-control" name="durasi" id="durasi" value="{{ $layanan->durasi }}" readonly required>
                            </div>
                        </div>

                        <div class="col mt-3">
                            <label for="durasi" class="form-label">Keterangan</label>
                            <textarea type="text" class="form-control" name="keterangan" required>{{ $layanan->keterangan }}</textarea>
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
</div>
@endforeach

@push('script')
<script>
    $(document).ready(function() {
        function getCurrentMonthRange() {
            let start = moment().startOf('month'); // Hari pertama bulan ini
            let end = moment().endOf('month'); // Hari terakhir bulan ini

            return {
                start,
                end
            };
        }

        let {
            start,
            end
        } = getCurrentMonthRange();

        $('.daterange').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
    });

    $(document).ready(function() {
        function initSelect2(modal) {
            $(modal).find('.select-bidang-modal, .select-kategori-modal, .select-sub-kategori-modal').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih",
                allowClear: true,
                width: '100%',
                dropdownParent: $(modal) // Memastikan dropdown tetap dalam modal
            });
        }

        // Inisialisasi saat modal terbuka
        $('#edit{{$layanan->id}}').on('shown.bs.modal', function() {
            initSelect2(this);
        });

        // Inisialisasi untuk elemen yang sudah ada di halaman
        initSelect2(document);
    });

    $(document).ready(function() {
        function initSelect2(selector, placeholder) {
            $(selector).select2({
                theme: 'bootstrap-5',
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }

        // Inisialisasi Select2 untuk semua dropdown yang diperlukan
        initSelect2('.select-bidang', 'Bidang');
        initSelect2('.select-kategori', 'Kategori');
        initSelect2('.select-pic', 'Person in Charge');

        // Re-inisialisasi Select2 saat jendela diubah ukurannya
        $(window).on('resize', function() {
            $('.select-bidang, .select-kategori, .select-pic').each(function() {
                $(this).select2('destroy').select2({
                    theme: 'bootstrap-5',
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%'
                });
            });
        });
    });

    function ucfirst(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : "";
    }

    function escapeHtml(text) {
        if (typeof text !== "string") {
            return text ?? ""; // Jika null atau undefined, kembalikan string kosong
        }
        return text.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);

        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
        const yyyy = date.getFullYear();
        const hh = String(date.getHours()).padStart(2, '0');
        const mi = String(date.getMinutes()).padStart(2, '0');
        const ss = String(date.getSeconds()).padStart(2, '0');

        return `${yyyy}-${mm}-${dd}`;
    }

    $(document).ready(function() {
        $(document).on('change', '.select-kategori-modal', function() {
            var kategoriID = $(this).val();
            var $modal = $(this).closest('.modal'); // Cari modal terdekat
            var $subKategoriSelect = $modal.find('.select-sub-kategori-modal');

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
                        $subKategoriSelect.empty().append('<option value="">Pilih Sub Kategori</option>');
                        $.each(data, function(key, value) {
                            $subKategoriSelect.append('<option value="' + value.id + '">' + value.sub_pelayanan + '</option>');
                        });
                    }
                });
            } else {
                $subKategoriSelect.empty().append('<option value="">Pilih Sub Kategori</option>');
            }
        });
    });

    $(document).ready(function() {
        function initSelect2(modal) {
            $(modal).find('.select-karyawan-modal').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Karyawan",
                allowClear: true,
                width: '100%',
                dropdownParent: $(modal) // Mencegah modal tertutup saat memilih opsi
            });
        }

        // Inisialisasi saat modal dibuka
        $('.modal').on('shown.bs.modal', function() {
            initSelect2(this);
        });

        // Event listener untuk pemilihan karyawan
        $(document).on('change', '.select-karyawan-modal', function() {
            var nik = $(this).val();
            var modal = $(this).closest('.modal'); // Ambil modal terdekat dari elemen yang berubah

            if (nik) {
                $.ajax({
                    url: '/get-karyawan/' + nik,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            modal.find('.nama-karyawan-modal').val(data.nama_karyawan);
                        } else {
                            modal.find('.nama-karyawan-modal').val('');
                        }
                    }
                });
            } else {
                modal.find('.nama-karyawan-modal').val('');
            }
        });
    });


    $(document).ready(function() {
        let table = $('#laporan-pelayanan').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            scrollY: '60vh',
            scrollCollapse: true
        });

        function fetchData() {
            let formData = $('#search-form').serialize();

            $.ajax({
                url: "{{ route('laporan-pelayanan.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    console.log(response); // Debugging: Lihat hasil response dari API

                    // Cek apakah response.pelayanan ada dan berupa array
                    if (!response || !response.pelayanan || !Array.isArray(response.pelayanan)) {
                        console.error("Data pelayanan tidak ditemukan atau bukan array.");
                        return;
                    }

                    table.clear().draw(); // Bersihkan tabel sebelum menambahkan data baru

                    response.pelayanan.forEach(function(layanan, index) {
                        table.row.add([
                            index + 1,
                            formatTimestamp(layanan.created_at || new Date()),
                            layanan.divisi ? layanan.divisi.divisi : '-',
                            layanan.kategori_pelayanan ? layanan.kategori_pelayanan.pelayanan : '-',
                            layanan.sub_kategori_pelayanan ? layanan.sub_kategori_pelayanan.sub_pelayanan : '-',
                            layanan.waktu_mulai || '-',
                            layanan.waktu_selesai || '-',
                            layanan.durasi || '-',
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(layanan.keterangan)}">
                                ${escapeHtml(layanan.keterangan.substring(0, 50))}...
                            </span>`,
                            layanan.pic ? layanan.pic.name : '-',
                            `<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-edit text-primary"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit${layanan.id}">
                                    <i class="bx text-primary bx-edit-alt me-2"></i> Edit
                                </a>
                                <a class="dropdown-item delete-btn" href="{{ route('laporan-pelayanan.destroy', ':id') }}".replace(':id', layanan.id)" data-confirm-delete="true">
                                    <i class="bx text-primary bx-trash me-2"></i> Delete
                                </a>
                            </div>
                        </div>`
                        ]).draw();
                    });

                    setTimeout(() => {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }, 100);
                },
                error: function(xhr) {
                    console.log("Error: ", xhr.responseText);
                }
            });
        }

        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });
    });
</script>
@endpush
@endsection