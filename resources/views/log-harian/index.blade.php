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

    #log-harian {
        table-layout: fixed;
        width: 100%;
    }

    #log-harian tbody tr {
        background: none !important;
    }

    #log-harian td:first-child,
    #log-harian th:first-child {
        width: 50px;
        /* Atur lebar yang sama */
        text-align: center;
    }

    #log-harian th,
    #log-harian td {
        text-align: center;
        white-space: nowrap;
    }

    #custom-tooltip {
        position: fixed;
        /* Gunakan fixed agar tidak bergantung pada scroll */
        display: none;
        background: rgba(0, 0, 0, 0.85);
        color: #fff;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 13px;
        max-width: 300px;
        pointer-events: none;
        z-index: 9999;
        white-space: pre-line;
    }

    th,
    td {
        white-space: nowrap;
        /* supaya tidak pecah baris */
    }

    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }

    #log-harian th:nth-child(1),
    #log-harian td:nth-child(1),
    #log-harian th:nth-child(2),
    #log-harian td:nth-child(2),
    #log-harian th:nth-child(3),
    #log-harian td:nth-child(3) {
        position: sticky;
        left: 0;
        background-color: white;
        z-index: 1;
    }

    /* Untuk kolom ke-2, 3, dst harus atur left-nya bertahap */
    #log-harian th:nth-child(1),
    #log-harian td:nth-child(1) {
        left: 60px;
        /* adjust sesuai lebar kolom pertama */
    }

    #log-harian th:nth-child(2),
    #log-harian td:nth-child(2) {
        left: 120px;
        /* dst. */
    }

    /* Header dan Footer kolom freeze */
    table.dataTable thead tr>.dtfc-fixed-left,
    table.dataTable thead tr>.dtfc-fixed-right,
    table.dataTable tfoot tr>.dtfc-fixed-left,
    table.dataTable tfoot tr>.dtfc-fixed-right {
        top: 0;
        bottom: 0;
        z-index: 2;
        /* Untuk menindih kolom biasa */
        background-color: #8c52ff !important;
        /* Warna ungu */
        color: white;
        /* Agar teks tetap terbaca */
    }

    /* Sel body kolom freeze */
    table.dataTable tbody tr>.dtfc-fixed-left,
    table.dataTable tbody tr>.dtfc-fixed-right {
        background-color: #f9f9f9;
        /* Warna isi kolom freeze */
        z-index: 1;
    }

    /* Untuk mencegah garis terpotong */
    table.dataTable .dtfc-fixed-left,
    table.dataTable .dtfc-fixed-right {
        box-shadow: inset -1px 0 #ddd;
    }

    /* (Opsional) Agar sticky header tertutup dengan halus */
    table.dataTable thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #8c52ff;
        color: white;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-2 py-1" style="max-width: 26rem; height: 2.5rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Pekerjaan Harian</h6>
            </div>
        </div>

        <a href="{{ route('log-harian.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Kegiatan
        </a>
    </div>

    <form id="search-form">
        <div class="row g-2 d-flex flex-wrap mb-3">
            @csrf
            <div class="col-12 col-sm-6 col-md-3">
                <select name="kategori_pekerjaan[]" class="form-control select-kategori-pekerjaan w-100">
                    <option value="" disabled selected>Kategori Pekerjaan</option>
                    @foreach($kategori_pekerjaan as $kp)
                    <option value="{{ $kp->id }}">{{ $kp->kategori_pekerjaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="prioritas[]" class="form-control select-prioritas w-100">
                    <option value="" disabled selected>Prioritas</option>
                    @foreach($prioritas as $priorit)
                    <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="pic[]" class="form-control select-pic w-100" id="select-pic">
                    <option value="" disabled selected>PIC</option>
                    @foreach($user as $user)
                    <option value="{{ $user->id }}">{{ strtoupper(strtolower($user->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group w-100">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" name="tanggal" class="form-control daterange" placeholder="Cari tanggal" />
                </div>
            </div>
            <div class="col-lg-12">
                <select class="form-select select-pekerjaan" name="pekerjaan[]" id="select-pekerjaan" multiple>
                    <option value="">Pilih Pekerjaan</option>
                </select>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="log-harian">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center text-white">No</th>
                        <td class="text-center text-white"></td>
                        <th class="text-center text-white">Deskripsi Pekerjaan</th>
                        <th class="text-center text-white">Tanggal Pencatatan</th>
                        <th class="text-center text-white">Sifat Pekerjaan</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Prioritas</th>
                        <th class="text-center text-white">Status Pekerjaan</th>
                        <th class="text-center text-white">Kategori Pekerjaan</th>
                        <th class="text-center text-white">Tanggal Mulai</th>
                        <th class="text-center text-white">Tanggal Selesai</th>
                        <th class="text-center text-white">Durasi Pekerjaan</th>
                        <th class="text-center text-white">Deadline</th>
                        <th class="text-center text-white">Penanggung Jawab</th>
                        <th class="text-center text-white">Tingkat Kesulitan</th>
                        <th class="text-center text-white">Alasan Kesulitan</th>
                        <th class="text-center text-white">Lampiran</th>
                        <th class="text-center text-white">Feedback Atasan</th>
                        <th class="text-center text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pekerjaan as $kerjaan)
                    <tr class="row-hover baris-utama" data-id="{{ $kerjaan->id }}" data-warna="{{ $kerjaan->warna_status }}">
                        <td class="text-center">{{ ++$no }}</td>
                        <td>
                            <button class="btn btn-sm toggle-btn {{ $kerjaan->getSubPekerjaan->isEmpty() ? 'btn-light-gray' : 'btn-primary' }}"
                                data-id="{{ $kerjaan->id }}">
                                {{ $kerjaan->getSubPekerjaan->isEmpty() ? '-' : '+' }}
                            </button>
                        </td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $kerjaan->deskripsi_pekerjaan }}">
                                {{ substr($kerjaan->deskripsi_pekerjaan, 0, 25) }}...
                            </span>
                        </td>
                        <td class="text-center">{{ date_format($kerjaan->created_at, 'd-m-Y') }}</td>
                        <td>{{ $kerjaan->getSifatPekerjaan->pekerjaan }}</td>
                        <td>{{ $kerjaan->getUser->name }}</td>
                        <td>{{ $kerjaan->getPrioritas->prioritas }}</td>
                        <td>
                            <select class="form-select form-select-sm main-status status-pekerjaan">
                                <option value="{{ $kerjaan->getStatusPekerjaan->id }}">{{ $kerjaan->getStatusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sp)
                                @if($kerjaan->getStatusPekerjaan->id != $sp->id)
                                <option value="{{ $sp->id }}">{{ $sp->status_pekerjaan }}</option>
                                @endif
                                @endforeach
                            </select>
                        </td>
                        <td>{{ $kerjaan->getKategoriPekerjaan->kategori_pekerjaan }}</td>
                        <td>{{ date('d-m-Y', strtotime($kerjaan->tanggal_mulai)) }}</td>
                        <td class="tanggal-selesai">{{ date('d-m-Y', strtotime($kerjaan->tanggal_selesai)) }}</td>
                        <td>{{ $kerjaan->durasi }}</td>
                        <td class="deadline"></td>
                        <td>{{ $kerjaan->getPjPekerjaan->name }}</td>
                        <td>{{ $kerjaan->tingkat_kesulitan }}/10</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $kerjaan->alasan }}">
                                {{ substr($kerjaan->alasan, 0, 25) }}...
                            </span>
                        <td>
                            <a class="nav-link" target="_blank" href="{{ asset('lampiran/pekerjaan/' . $kerjaan->lampiran) }}"><i class="bx bx-link-alt me-1"></i>{{ $kerjaan->lampiran ?? '---' }}</a>
                        </td>
                        <td>

                            <small class="text-light fst-italic fw-semibold">{{ $kerjaan->feedback_atasan ?? 'Belum ada feedback' }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn text-primary p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-{{$kerjaan->id}}"><i class="bx text-primary bx-plus-circle me-2"></i> Tambah</a>
                                    <a class="dropdown-item" href="{{ route('log-harian.edit', $kerjaan->id) }}"><i class="bx text-primary bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('log-harian.destroy', $kerjaan->id) }}" data-confirm-delete="true"><i class="bx text-primary bx-trash me-2"></i> Delete</a>
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

<!-- Modal add -->
@include('log-harian.modal.modal-add')
<!-- Modal end -->

@push('script')
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Fungsi untuk memperbarui warna select berdasarkan nilai yang dipilih
        window.updateSelectColor = function(select) {
            let selectedValue = select.value;

            // Reset semua class warna
            select.classList.remove(
                "text-danger", "text-primary", "text-success", "text-secondary", "text-warning", "text-info",
                "bg-label-danger", "bg-label-primary", "bg-label-success", "bg-label-secondary", "bg-label-warning", "bg-label-info"
            );

            // Tambahkan class warna sesuai status
            switch (selectedValue) {
                case "1":
                    select.classList.add("text-danger", "bg-label-danger");
                    break;
                case "2":
                    select.classList.add("text-primary", "bg-label-primary");
                    break;
                case "3":
                    select.classList.add("text-warning", "bg-label-warning");
                    break;
                case "4":
                    select.classList.add("text-secondary", "bg-label-secondary");
                    break;
                case "5":
                    select.classList.add("text-info", "bg-label-info");
                    break;
                case "6":
                    select.classList.add("text-success", "bg-label-success");
                    break;

            }
        };

        // Inisialisasi warna untuk semua elemen yang sudah ada di halaman
        document.querySelectorAll(".main-status, .sub-status").forEach(updateSelectColor);

        // Event listener untuk perubahan status (gunakan event delegation)
        document.addEventListener("change", function(event) {
            if (event.target.matches(".main-status, .sub-status")) {
                updateSelectColor(event.target);
            }
        });

        // Pastikan warna diperbarui setelah DataTables reload
        $('#log-harian').on('draw.dt', function() {
            document.querySelectorAll(".main-status, .sub-status").forEach(updateSelectColor);
        });
    });

    function updateRowColors() {
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        // Proses baris utama + sub baris
        document.querySelectorAll("tr.baris-utama, tr.sub-baris").forEach(row => {
            let cellTanggal = row.querySelector(".tanggal-selesai");
            let selectStatus = row.querySelector(".status-pekerjaan");
            let id = row.dataset.id; // gunakan data-id untuk semua baris
            let warnaDb = row.dataset.warna;

            if (!cellTanggal || !selectStatus || !id) return;

            row.classList.remove("table-danger", "table-warning", "table-success");

            let statusText = selectStatus.options[selectStatus.selectedIndex].text.trim().toLowerCase();
            let warnaBaru = null;

            if (statusText === "selesai" || statusText === "selesai dan diterima") {
                // Jika sudah selesai, pakai warna dari DB untuk tampilan
                if (warnaDb === "merah") {
                    row.classList.add("table-danger");
                } else if (warnaDb === "kuning") {
                    row.classList.add("table-warning");
                } else if (warnaDb === "hijau") {
                    row.classList.add("table-success");
                }
                warnaBaru = warnaDb;
            } else {
                let tanggalText = cellTanggal.innerText.trim();
                let [day, month, year] = tanggalText.split("-");
                let tanggalSelesai = new Date(year, month - 1, day);
                tanggalSelesai.setHours(0, 0, 0, 0);

                let selisihHari = Math.ceil((tanggalSelesai - today) / (1000 * 60 * 60 * 24));

                if (selisihHari >= 2 && selisihHari <= 3) {
                    warnaBaru = 'hijau';
                    row.classList.add("table-success");
                } else if (selisihHari >= 0 && selisihHari <= 1) {
                    warnaBaru = 'kuning';
                    row.classList.add("table-warning");
                } else if (selisihHari < 0) {
                    warnaBaru = 'merah';
                    row.classList.add("table-danger");
                }
            }

            if (warnaBaru !== warnaDb) {
                updateWarnaDB(id, warnaBaru, row);
            }
        });
    }

    function updateWarnaDB(id, warna, row) {
        if (!warna) {
            // console.warn(`Warna kosong untuk ID ${id}, skip update`);
            return;
        }

        $.ajax({
            url: "/log-harian/update-warna-status/" + id,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                warna_status: warna
            },
            success: function(response) {
                // console.log(`Warna updated (ID: ${id}):`, warna);
                row.dataset.warna = warna;
            },
            error: function(xhr) {
                console.error(`Gagal update warna untuk ID ${id}`, xhr.responseText);
                // Optional: tampilkan alert atau Swal
            }
        });
    }

    // Jalankan fungsi saat halaman dimuat
    document.addEventListener("DOMContentLoaded", updateRowColors);

    // Jalankan lagi jika data diperbarui via AJAX
    $(document).on('xhr.dt', function() {
        setTimeout(updateRowColors, 300); // Tunggu data baru di-load
    });

    // Jalankan update warna saat status pekerjaan berubah
    $(document).on("change", ".status-pekerjaan", function() {
        updateRowColors();
    });

    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];

        // Pilih semua elemen dengan class 'tanggalPelaporan' (gunakan class untuk multiple elements)
        document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
            input.value = today;
        });
    });

    $(document).ready(function() {
        $('.select-kategori-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Kategori Pekerjaan",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $('.select-prioritas').select2({
            theme: 'bootstrap-5',
            placeholder: "Prioritas",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });

        $('.select-pic').select2({
            theme: 'bootstrap-5',
            placeholder: "PIC",
            allowClear: true,
            width: '100%'
        });

        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true,
            width: '100%'
        });

        $('#select-pic').on('change', function() {
            let userId = $(this).val();

            if (userId) {
                // Request list pekerjaan via AJAX
                $.ajax({
                    url: '/log-harian/by-user/' + userId, // Ganti URL sesuai route Laravel Anda
                    type: 'GET',
                    success: function(data) {
                        let $pekerjaanSelect = $('#select-pekerjaan');
                        $pekerjaanSelect.empty().append('<option value=""></option>');

                        data.forEach(function(item) {
                            $pekerjaanSelect.append(
                                $('<option>', {
                                    value: item.id,
                                    text: item.deskripsi_pekerjaan // ganti sesuai field
                                })
                            );
                        });

                        $pekerjaanSelect.prop('disabled', false).trigger('change');
                    },
                    error: function() {
                        alert('Gagal mengambil data pekerjaan.');
                    }
                });
            } else {
                $('#select-pekerjaan').empty().append('<option value=""></option>').prop('disabled', true).trigger('change');
            }
        });
    });

    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);

        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
        const yyyy = date.getFullYear();
        const hh = String(date.getHours()).padStart(2, '0');
        const mi = String(date.getMinutes()).padStart(2, '0');
        const ss = String(date.getSeconds()).padStart(2, '0');

        return `${dd}-${mm}-${yyyy}`;
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

    $.ajax({
        url: "/log-harian",
        type: "GET",
        success: function(response) {
            let statusOptions = "";

            response.status_pekerjaan.forEach(function(status) {
                statusOptions += `<option value="${status.id}">${status.status_pekerjaan}</option>`;
            });

            // Simpan ke variabel global agar bisa dipakai di sub-table
            window.statusOptions = statusOptions;
        }
    });

    function getBasename(path) {
        return path.split('/').pop();
    }

    $(document).ready(function() {
        let table = $('#log-harian').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3 // ⬅️ mengunci kolom ke-1 sampai ke-4
            },
            columnDefs: [{
                targets: 14, // kolom tingkat kesulitan
                type: 'num', // pastikan dianggap sebagai numerik
                render: function(data, type, row) {
                    if (type === 'sort') {
                        return parseInt(data.split('/')[0], 10); // ambil angka sebelum "/"
                    }
                    return data; // tampilkan nilai asli
                }
            }]
        });

        function fetchData() {
            let formData = $('#search-form').serialize();

            $.ajax({
                url: "{{ route('log-harian.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    console.log(response);

                    table.clear().draw();

                    response.pekerjaan.forEach(function(kerjaan, index) {

                        let statusOptions = response.status_pekerjaan.map(sp =>
                            `<option value="${sp.id}" ${kerjaan.get_status_pekerjaan.id === sp.id ? "selected" : ""}>${sp.status_pekerjaan}</option>`
                        ).join("");

                        let toggleIcon = kerjaan.get_sub_pekerjaan.length > 0 ?
                            '<button class="btn btn-sm btn-primary toggle-btn" data-id="' + kerjaan.id + '">+</button>' :
                            '<button class="btn btn-sm btn-primary toggle-btn" data-id="' + kerjaan.id + '">-</button>';

                        table.row.add([
                            index + 1,
                            toggleIcon,
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(kerjaan.deskripsi_pekerjaan)}">
                                ${escapeHtml(kerjaan.deskripsi_pekerjaan.substring(0, 25))}...
                            </span>`,
                            formatTimestamp(kerjaan.created_at),
                            kerjaan.get_sifat_pekerjaan.pekerjaan,
                            kerjaan.get_user.name,
                            kerjaan.get_prioritas.prioritas,
                            `<select class="form-select form-select-sm main-status status-pekerjaan">
                                ${statusOptions}
                            </select>`,
                            kerjaan.get_kategori_pekerjaan.kategori_pekerjaan,
                            formatTimestamp(kerjaan.tanggal_mulai),
                            `<span class="tanggal-selesai">${formatTimestamp(kerjaan.tanggal_selesai)}</span>`,
                            kerjaan.durasi,
                            `<div class="deadline"></div>`,
                            kerjaan.get_pj_pekerjaan.name,
                            kerjaan.tingkat_kesulitan + "/10",
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(kerjaan.alasan)}">
                                ${escapeHtml(kerjaan.alasan.substring(0, 25))}...
                            </span>`,
                            kerjaan.lampiran ?
                            `<a class="nav-link" target="_blank" href="/lampiran/pekerjaan/${kerjaan.lampiran}">
                                <i class="bx bx-link-alt me-1"></i> ${getBasename(kerjaan.lampiran)}
                            </a>` :
                            `<a class="nav-link" target="_blank" href="#">
                                <i class="bx bx-link-alt me-1"></i> ---
                            </a>`,
                            kerjaan.feedback_atasan ?
                            `<small class="text-light fst-italic fw-semibold"> ${kerjaan.feedback_atasan} </small>` :
                            `<small class="text-light fst-italic fw-semibold"> Belum ada feedbback </small>`,
                            '<div class="dropdown">' +
                            '<button type="button" class="btn text-primary p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                            '<i class="bx bx-edit"></i>' +
                            '</button>' +
                            '<div class="dropdown-menu">' +
                            '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-' + kerjaan.id + '">' +
                            '<i class="bx text-primary bx-plus-circle me-2"></i> Tambah' +
                            '</a>' +
                            '<a class="dropdown-item" href="' + "{{ route('log-harian.edit', ':id') }}".replace(':id', kerjaan.id) + '">' +
                            '<i class="bx text-primary bx-edit-alt me-2"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item delete-btn" href="' + "{{ route('log-harian.destroy', ':id') }}".replace(':id', kerjaan.id) + '" data-confirm-delete="true">' +
                            '<i class="bx text-primary bx-trash me-2"></i> Delete' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ]).draw();

                        let dtRow = table.row(':last');
                        let node = dtRow.node();
                        $(node).attr('data-warna', kerjaan.warna_status); // warna_status dari DB
                        $(node).addClass('baris-utama');
                        $(node).attr('data-id', kerjaan.id); // penting untuk updateRowColors
                    });
                    setTimeout(updateRowColors, 500);
                    updateDeadline(); //
                    setTimeout(() => {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }, 100);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Update status pekerjaan
        $('#log-harian tbody').on('change', '.main-status', function() {
            let newStatusId = $(this).val(); // Ambil ID status yang dipilih
            let kerjaanId = $(this).closest('tr').find('.toggle-btn').data('id'); // Ambil ID pekerjaan dari tombol toggle

            if (!kerjaanId) {
                Swal.fire({
                    title: "Error!",
                    text: "ID pekerjaan tidak ditemukan!",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            // Kirim update ke backend
            $.ajax({
                url: "/log-harian/update-status-pekerjaan/" + kerjaanId,
                type: "POST",
                data: {
                    status_pekerjaan_id: newStatusId
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: response.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    fetchData();
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        title: "Gagal!",
                        text: response ? response.message : "Terjadi kesalahan.",
                        icon: "error"
                    });

                    fetchData();
                }
            });
        });

        // Update sub status pekerjaan 
        $('#log-harian tbody').on('change', '.sub-status', function() {
            let newStatusId = $(this).val();
            let subPekerjaanId = $(this).data('id');
            let $select = $(this);

            if (!subPekerjaanId) {
                Swal.fire({
                    title: "Error!",
                    text: "ID pekerjaan tidak ditemukan!",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                url: "/log-harian/sub/update-status-pekerjaan/" + subPekerjaanId,
                type: "POST",
                data: {
                    status_pekerjaan_id: newStatusId
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Status sub pekerjaan telah diperbarui.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // ✅ Update warna <select>
                    updateSelectColor($select[0]);

                    // ✅ Update warna baris setelah status diubah
                    updateRowColors();

                    // Tidak perlu update DOM manual, karena <select> sudah mencerminkan pilihan baru.
                },
                error: function() {
                    $select.val($select.data('current')); // Rollback pilihan ke yang sebelumnya
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat memperbarui status sub pekerjaan.",
                        icon: "error",
                        confirmButtonText: "Coba Lagi"
                    });

                    // (Opsional) bisa rollback pilihan dropdown jika perlu
                }
            });
        });

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

            $('.daterange')
                .attr('placeholder', 'Cari tanggal')
                .daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false, // Ini penting supaya input nggak langsung keisi
                    locale: {
                        format: 'DD-MM-YYYY',
                        cancelLabel: 'Batal',
                        applyLabel: 'Terapkan'
                    }
                });

            // Isi input saat tanggal dipilih
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                const value = picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY');
                $(this).val(value); // <- penting! agar masuk saat serialize()
                fetchData(); // kirim data via AJAX
            });

            // Kosongkan input saat klik batal
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                fetchData();
            });
        });

        // Trigger pencarian otomatis saat filter berubah
        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });

        // Fungsi untuk menampilkan sub pekerjaan
        $('#log-harian tbody').on('click', '.toggle-btn', function() {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let id = $(this).data('id');
            let toggleBtn = $(this);

            // Cek apakah sub-row sudah ada
            if ($(tr).next().hasClass('sub-row')) {
                // Jika sudah ada, hapus semua sub-row terkait
                $(tr).nextAll('.sub-row').remove();
                toggleBtn.html('+').removeClass('btn-primary').addClass('btn-light-gray');
            } else {
                // Ubah ikon tombol menjadi loading sebelum AJAX dipanggil
                toggleBtn.html('⏳');

                // Ambil data sub-pekerjaan melalui AJAX sebelum menampilkan sub-row
                $.ajax({
                    url: "/log-harian/sub/" + id,
                    type: "GET",
                    success: function(subPekerjaan) {
                        let subRows = '';

                        if (subPekerjaan.length === 0) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Belum ada sub kegiatan",
                                icon: "info",
                                timer: 1500,
                                showConfirmButton: true
                            });
                            toggleBtn.html('-').removeClass('btn-primary').addClass('btn-light-gray');
                        } else {
                            subRows = subPekerjaan.map((sub, index) => `
                    <tr class="sub-row sub-baris table-hover row-hover" data-id="${sub.id}" data-warna="${sub.warna_status}">
                        <td></td>
                        <td>${index + 1}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(sub.sub_deskripsi_pekerjaan || sub.deskripsi_pekerjaan)}">
                                ${escapeHtml((sub.sub_deskripsi_pekerjaan || sub.deskripsi_pekerjaan).substring(0, 25))}...
                            </span>
                        </td>
                        <td>${formatTimestamp(sub.created_at)}</td>
                        <td>${escapeHtml(sub.get_sifat_pekerjaan.pekerjaan)}</td>
                        <td>${escapeHtml(sub.get_user.name)}</td>
                        
                        <td>${escapeHtml(sub.get_prioritas.prioritas)}</td>
                        <td>
                            <select class="form-select status-pekerjaan form-select-sm sub-status" data-id="${sub.id}" data-current="${sub.get_status_pekerjaan.id}">
                                <option value="${sub.get_status_pekerjaan.id}" selected>${escapeHtml(sub.get_status_pekerjaan.status_pekerjaan)}</option>
                                ${statusOptions}
                            </select>
                        </td>
                        <td>${escapeHtml(sub.get_kategori_pekerjaan.kategori_pekerjaan)}</td>
                        <td>${formatTimestamp(sub.tanggal_mulai)}</td>
                        <td class="tanggal-selesai">${formatTimestamp(sub.tanggal_selesai)}</td>
                        <td>${escapeHtml(sub.durasi)}</td>
                        <td class="deadline">${escapeHtml(sub.deadline)}</td>
                        <td>${escapeHtml(sub.get_pj_pekerjaan.name)}</td>
                        <td>${escapeHtml(sub.tingkat_kesulitan)}/10</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(sub.alasan)}">
                                ${escapeHtml(sub.alasan.substring(0, 25))}...
                            </span>
                        </td>
                        <td>${sub.lampiran && sub.lampiran.trim() !== "" ? 
                                `<a class="nav-link" target="_blank" href="/lampiran/pekerjaan/sub/${sub.lampiran}">
                                     <i class="bx bx-link-alt me-1"></i> ${sub.lampiran}
                                 </a>` : `<a class="nav-link" href="#">
                                     <i class="bx bx-link-alt me-1"></i> ---
                                 </a>`}
                        </td>
                        <td>${sub.feedback_atasan ? 
                            `<small class="text-light fst-italic fw-semibold">${sub.feedback_atasan}</small>` : 
                            `<small class="text-light fst-italic fw-semibold">Belum ada feedback</small>`}
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn text-primary p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="${"{{ route('log-harian.edit.sub', ':id') }}".replace(':id', sub.id)}">
                                        <i class="bx text-primary bx-edit-alt me-2"></i> Edit
                                    </a>
                                    <a class="dropdown-item delete-btn" href="${"{{ route('log-harian.destroy.sub', ':id') }}".replace(':id', sub.id)}" data-confirm-delete="true">
                                        <i class="bx text-primary bx-trash me-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `).join('');

                            // Tambahkan sub-row setelah baris utama
                            $(tr).after(subRows);
                            toggleBtn.html('-').removeClass('btn-light-gray').addClass('btn-primary');
                        }

                        updateDeadline();
                        setTimeout(updateRowColors, 100);
                        setTimeout(() => {
                            document.querySelectorAll(".sub-status").forEach(function(select) {
                                updateSelectColor(select);
                            });
                        }, 100);
                    },
                });
            }
        });
    });

    function calculateDays(event) {
        const modal = event.target.closest(".modal"); // Cari modal terdekat dari elemen yang berubah
        if (!modal) return;

        const startDateInput = modal.querySelector(".tanggalMulai");
        const endDateInput = modal.querySelector(".tanggalSelesai");
        const durationInput = modal.querySelector(".duration");

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (!isNaN(startDate) && !isNaN(endDate)) {
            if (endDate < startDate) {
                Swal.fire({
                    icon: "error",
                    title: "Tanggal Tidak Valid!",
                    text: "Tanggal Selesai tidak boleh lebih kecil dari Tanggal Mulai.",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                });

                endDateInput.value = ""; // Reset input
                durationInput.value = "";
                return;
            }

            const timeDiff = endDate - startDate;
            const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
            durationInput.value = daysDiff + " Hari";
        } else {
            durationInput.value = "";
        }
    }

    // Event delegation untuk menangani semua modal
    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("tanggalMulai") || event.target.classList.contains("tanggalSelesai")) {
            calculateDays(event);
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        function initializeSliders(modal) {
            modal.querySelectorAll(".slider-container").forEach(container => {
                const slider = container.querySelector(".slider-range");
                const sliderValue = container.querySelector(".slider-value");

                function updateValuePosition() {
                    const percent = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
                    sliderValue.style.left = `calc(${percent}% - 5px)`;
                    sliderValue.textContent = slider.value;
                }

                // Pastikan jika nilai sudah ada, gunakan yang ada. Jika tidak, set default 8
                slider.value = slider.value || 8;
                updateValuePosition();

                slider.addEventListener("input", updateValuePosition);
            });
        }

        // Pastikan kode dijalankan setiap kali modal dibuka
        document.querySelectorAll(".modal").forEach(modal => {
            modal.addEventListener("shown.bs.modal", function() {
                initializeSliders(modal);
            });
        });
    });


    $(document).on("change", ".fileInput", function() {
        let fileId = $(this).attr("id"); // Dapatkan ID input file (misal: fileInput1)
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";

        // Ubah label & nama file berdasarkan ID yang sesuai
        $("#fileLabel" + fileId.replace("fileInput", "")).text(fileName);
        $("#fileName" + fileId.replace("fileInput", "")).text(fileName);
    });

    // $(document).on("change", ".fileInputEdit", function() {
    //     let fileId = $(this).attr("id"); // Dapatkan ID input file (misal: fileInput1)
    //     let fileName = this.files[0] ? this.files[0].name : "Pilih File";

    //     // Ubah label & nama file berdasarkan ID yang sesuai
    //     $("#fileLabelEdit" + fileId.replace("fileInputEdit", "")).text(fileName);
    //     $("#fileNameEdit" + fileId.replace("fileInputEdit", "")).text(fileName);
    // });

    // $(document).on("change", ".fileInputSubEdit", function() {
    //     let fileId = $(this).attr("id"); // Dapatkan ID input file (misal: fileInput1)
    //     let fileName = this.files[0] ? this.files[0].name : "Pilih File";

    //     // Ubah label & nama file berdasarkan ID yang sesuai
    //     $("#fileLabelSubEdit" + fileId.replace("fileInputSubEdit", "")).text(fileName);
    //     $("#fileNameSubEditSub" + fileId.replace("fileInputSubEdit", "")).text(fileName);
    // });

    document.addEventListener("DOMContentLoaded", function() {
        function calculateDays(startInput, endInput, durationInput, deadlineInput) {
            const startDate = new Date(startInput.value);
            const endDate = new Date(endInput.value);

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
                    endInput.value = "";
                    durationInput.value = "";
                    deadlineInput.value = "";
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
                durationInput.value = daysDiff + " Hari";
                deadlineInput.value = selisih;
            } else {
                durationInput.value = "";
                deadlineInput.value = "";
            }
        }

        // Loop untuk setiap modal yang ada di halaman
        document.querySelectorAll(".modal").forEach(modal => {
            const startInput = modal.querySelector(".tanggalMulai");
            const endInput = modal.querySelector(".tanggalSelesai");
            const durationInput = modal.querySelector(".duration");
            const deadlineInput = modal.querySelector(".deadline");

            if (startInput && endInput && durationInput && deadlineInput) {
                startInput.addEventListener("change", () => calculateDays(startInput, endInput, durationInput, deadlineInput));
                endInput.addEventListener("change", () => calculateDays(startInput, endInput, durationInput, deadlineInput));
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        updateDeadline();
    });

    function parseDMY(dateStr) {
        if (!dateStr || !dateStr.includes("-")) return new Date("Invalid");
        const [day, month, year] = dateStr.split("-");
        return new Date(year, month - 1, day);
    }

    // Fungsi untuk mengupdate deadline di tabel utama dan sub-tabel
    function updateDeadline() {
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset waktu agar lebih akurat

        function processRow(row) {
            let cellTanggalSelesai = row.querySelector("td:nth-child(11)");
            let cellDeadline = row.querySelector("td:nth-child(13)");

            if (!cellTanggalSelesai || !cellDeadline) return;

            let tanggalText = cellTanggalSelesai.innerText.trim();
            let tanggalSelesai = parseDMY(tanggalText);
            if (isNaN(tanggalSelesai)) {
                cellDeadline.innerText = "H-0"; // fallback jika format salah
                return;
            }

            tanggalSelesai.setHours(0, 0, 0, 0);
            let selisihHari = Math.ceil((tanggalSelesai - today) / (1000 * 60 * 60 * 24));

            if (selisihHari < 0) {
                selisihHari = 'H+' + String(selisihHari).replace('-', '');
            } else {
                selisihHari = 'H-' + String(selisihHari).replace('-', '');
            }

            cellDeadline.innerText = selisihHari;
        }

        // Tabel utama
        document.querySelectorAll("#log-harian tbody tr:not(.sub-row)").forEach(processRow);

        // Sub-tabel
        document.querySelectorAll("#log-harian tbody tr.sub-row").forEach(processRow);
    }
</script>
@endpush

@endsection