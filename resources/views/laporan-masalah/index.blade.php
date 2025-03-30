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
                <h6 class="card-title text-white fw-bold m-2 text-center">Laporan Masalah</h6>
            </div>
        </div>
        <a href="{{ route('laporan-masalah.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Masalah
        </a>
    </div>

    <!-- Basic Bootstrap Table -->
    <form id="search-form">
        <div class="row g-2 d-flex flex-wrap mb-3">
            @csrf
            <div class="col-12 col-sm-6 col-md-3">
                <select name="pekerjaan[]" class="form-control select-pekerjaan w-100">
                    <option value="" disabled selected>Pekerjaan</option>
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
            <table class="table" id="laporan-masalah">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center text-white">No</th>
                        <th class="text-center text-white">Tanggal Pengaduan</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Deskripsi Pekerjaan</th>
                        <th class="text-center text-white">Deskripsi Kendala</th>
                        <th class="text-center text-white">Status Penyelesaian</th>
                        <th class="text-center text-white">Kategori Kendala</th>
                        <th class="text-center text-white">Tingkat Dampak</th>
                        <th class="text-center text-white">Alasan Tingkat Dampak</th>
                        <th class="text-center text-white">Divisi</th>
                        <th class="text-center text-white">Langkah Penyelesaian</th>
                        <th class="text-center text-white">Doc Permasalahan</th>
                        <th class="text-center text-white">Doc Analisa</th>
                        <th class="text-center text-white">Doc Solusi</th>
                        <th class="text-center text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($pengaduan as $pengaduan)
                    <tr data-id="{{ $pengaduan->id }}">
                        <td>{{ ++$no }}</td>
                        <td>{{ date('Y-m-d', strtotime($pengaduan->created_at)) }}</td>
                        <td>{{ $pengaduan->pic->name }}</td>
                        <td>{{ $pengaduan->pekerjaan->deskripsi_pekerjaan }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $pengaduan->deskripsi_pengaduan }}">
                                {{ substr($pengaduan->deskripsi_pengaduan, 0, 25) }}
                            </span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm main-status status-pekerjaan">
                                <option value="{{ $pengaduan->statusPekerjaan->id }}">{{ $pengaduan->statusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sp)
                                @if($pengaduan->statusPekerjaan->id != $sp->id)
                                <option value="{{ $sp->id }}">{{ $sp->status_pekerjaan }}</option>
                                @endif
                                @endforeach
                            </select>
                        </td>
                        <td>{{ ucfirst($pengaduan->kategori_kendala) }}</td>
                        <td>{{ $pengaduan->prioritas->prioritas }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $pengaduan->alasan_tingkat_dampak_pengaduan }}">
                                {{ substr($pengaduan->alasan_tingkat_dampak_pengaduan, 0, 25) }}
                            </span>
                        </td>
                        <td>-</td>

                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $pengaduan->langkah_penyelesaian }}">
                                {{ substr($pengaduan->langkah_penyelesaian, 0, 25) }}...
                            </span>
                        </td>
                        <td>
                            <a class="nav-link" target="_blank" href="{{ asset('storage/' . $pengaduan->doc_permasalahan) }}">
                                <i class="bx bx-link-alt me-1"></i> {{ $pengaduan->doc_permasalahan }}
                            </a>
                        </td>
                        <td>
                            <a class="nav-link" target="_blank" href="{{ asset('storage/' . $pengaduan->doc_analisis_risiko) }}">
                                <i class="bx bx-link-alt me-1"></i> {{ $pengaduan->doc_analisis_risiko }}
                            </a>
                        </td>
                        <td>
                            <a class="nav-link" target="_blank" href="{{ asset('storage/' . $pengaduan->doc_solusi) }}">
                                <i class="bx bx-link-alt me-1"></i> {{ $pengaduan->doc_solusi ?? '---' }}
                            </a>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit text-primary"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit{{$pengaduan->id}}"><i class="bx text-primary bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('laporan-masalah.destroy', $pengaduan->id) }}" data-confirm-delete="true"><i class="bx text-primary bx-trash me-2"></i> Delete</a>
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

@foreach($pengaduan_modal as $p_modal)
<div class="modal fade" id="edit{{$p_modal->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Laporan Masalah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan-masalah.update', $p_modal->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
                            <div class="card-body p-0">
                                <h6 class="card-title text-white fw-bold m-2 text-center">Form Pengaduan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" name="tanggal_pelaporan" id="tanggalPelaporan" value="{{ date('d/m/Y', strtotime($pengaduan->created_at)) }}" readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jenisKegiatan" class="form-label">Jenis Pekerjaan</label>
                            <select id="jenisKegiatan" name="jenis_pekerjaan_id" class="form-select" required>
                                <option value="{{ $p_modal->pekerjaan_id }}">{{ $p_modal->pekerjaan->deskripsi_pekerjaan }}</option>
                                @foreach($pekerjaan_modal as $pk_modal)
                                <option value="{{ $pk_modal->id }}">{{ $pk_modal->deskripsi_pekerjaan }} | {{ $pk_modal->tanggal_mulai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="divisi" class="form-label">Kategori Kendala</label>
                            <select id="divisi" name="kategori_kendala" class="form-select" required>
                                <option value="{{ $p_modal->kategori_kendala }}">{{ ucfirst($p_modal->kategori_kendala) }}</option>
                                <option value="manusia">Manusia</option>
                                <option value="metode">Metode</option>
                                <option value="mesin">Mesin</option>
                                <option value="material">Material</option>
                                <option value="pengukuran">Pengukuran</option>
                                <option value="lingkungan">Lingkungan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Tingkat Dampak</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option value="{{ $p_modal->prioritas_id }}">{{ $p_modal->prioritas->prioritas }}</option>
                                @foreach($prioritas as $prioriti)
                                <option value="{{ $prioriti->id }}">{{ $prioriti->prioritas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusKegiatan" class="form-label">Status Penyelesaian</label>
                            <select id="statusKegiatan" name="status_pekerjaan_id" class="form-select" required>
                                <option value="{{ $p_modal->status_pekerjaan_id }}">{{ $p_modal->statusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sk)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="alasanPenentuanTingkatDampak" class="form-label">Alasan Penentuan Tingkat Dampak</label>
                            <input class="form-control" name="alasan_tingkat_dampak_pengaduan" id="alasanPenentuanTingkatDampak" value="{{ $p_modal->alasan_tingkat_dampak_pengaduan }}" required></input>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Kendala</label>
                            <textarea class="form-control" name="deskripsi_pengaduan" id="deskripsiTugas" placeholder="Isi deksripsi" rows="2" required>{{ $p_modal->deskripsi_pengaduan }}</textarea>
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
@endforeach

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];

        // Pilih semua elemen dengan class 'tanggalPelaporan' (gunakan class untuk multiple elements)
        document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
            input.value = today;
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

        return `${yyyy}-${mm}-${dd}`;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Fungsi untuk memperbarui warna select berdasarkan nilai yang dipilih
        window.updateSelectColor = function(select) {
            let selectedValue = select.value;

            // Reset semua class warna
            select.classList.remove(
                "text-danger", "text-primary", "text-success",
                "text-secondary", "text-warning",
                "bg-label-danger", "bg-label-primary",
                "bg-label-success", "bg-label-secondary", "bg-label-warning"
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
                    select.classList.add("text-success", "bg-label-success");
                    break;
            }
        };

        // Inisialisasi warna untuk semua elemen yang sudah ada di halaman
        document.querySelectorAll(".main-status").forEach(updateSelectColor);

        // Event listener untuk perubahan status (gunakan event delegation)
        document.addEventListener("change", function(event) {
            if (event.target.matches(".main-status, .sub-status")) {
                updateSelectColor(event.target);
            }
        });

        // Pastikan warna diperbarui setelah DataTables reload
        $('#laporan-masalah').on('draw.dt', function() {
            document.querySelectorAll(".main-status").forEach(updateSelectColor);
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

        $('.daterange').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
    });

    $(document).ready(function() {
        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    $(document).ready(function() {
        $('.select-prioritas').select2({
            theme: 'bootstrap-5',
            placeholder: "Prioritas",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    $(document).ready(function() {
        $('.select-pic').select2({
            theme: 'bootstrap-5',
            placeholder: "PIC",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    $.ajax({
        url: "/laporan-masalah",
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

    function ucfirst(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : "";
    }

    $(document).ready(function() {
        let table = $('#laporan-masalah').DataTable({
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
                url: "{{ route('laporan-masalah.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    table.clear().draw();

                    console.log(response)

                    response.pengaduan.forEach(function(pengaduan, index) {
                        let statusOptions = response.status_pekerjaan.map(sp =>
                            `<option value="${sp.id}" ${pengaduan.status_pekerjaan && pengaduan.status_pekerjaan.id === sp.id ? "selected" : ""}>${sp.status_pekerjaan}</option>`
                        ).join("");

                        table.row.add([
                            index + 1,
                            formatTimestamp(pengaduan.created_at),
                            pengaduan.pic.name,
                            escapeHtml(pengaduan.pekerjaan.deskripsi_pekerjaan),
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(pengaduan.deskripsi_pengaduan)}">
                                ${escapeHtml(pengaduan.deskripsi_pengaduan.substring(0, 25))}...
                            </span>`,
                            `<select class="form-select form-select-sm main-status status-pekerjaan">
                                ${statusOptions}
                            </select>`,
                            ucfirst(pengaduan.kategori_kendala),
                            pengaduan.prioritas.prioritas,
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(pengaduan.alasan_tingkat_dampak_pengaduan)}">
                                ${escapeHtml(pengaduan.alasan_tingkat_dampak_pengaduan.substring(0, 25))}...
                            </span>`,
                            '-',
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(pengaduan.langkah_penyelesaian)}">
                                ${escapeHtml(pengaduan.langkah_penyelesaian.substring(0, 25))}...
                            </span>`,
                            pengaduan.doc_permasalahan ?
                            `<a class="nav-link" target="_blank" href="/storage/${pengaduan.doc_permasalahan}">
                                <i class="bx bx-link-alt me-1"></i> ${pengaduan.doc_permasalahan}
                            </a>` : '---',
                            pengaduan.doc_analisis_risiko ?
                            `<a class="nav-link" target="_blank" href="/storage/${pengaduan.doc_analisis_risiko}">
                                <i class="bx bx-link-alt me-1"></i> ${pengaduan.doc_analisis_risiko}
                            </a>` : '---',
                            pengaduan.doc_solusi ?
                            `<a class="nav-link" target="_blank" href="/storage/${pengaduan.doc_solusi}">
                                <i class="bx bx-link-alt me-1"></i> ${pengaduan.doc_solusi}
                            </a>` : '---',
                            '<div class="dropdown">' +
                            '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                            '<i class="bx bx-edit text-primary"></i>' +
                            '</button>' +
                            '<div class="dropdown-menu">' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit' + pengaduan.id + '">' +
                            '<i class="bx text-primary bx-edit-alt me-2"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item delete-btn" href="' + "{{ route('laporan-masalah.destroy', ':id') }}".replace(':id', pengaduan.id) + '" data-confirm-delete="true">' +
                            '<i class="bx text-primary bx-trash me-2"></i> Delete' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ]).draw().node().setAttribute('data-id', pengaduan.id);;
                    });

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
        $('#laporan-masalah tbody').on('change', '.main-status', function() {
            let newStatusId = $(this).val(); // Ambil ID status yang dipilih
            let pengaduanId = $(this).closest('tr').data('id'); // Ambil ID pengaduan

            if (!pengaduanId) {
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
                url: "/laporan-masalah/update-status-pekerjaan/" + pengaduanId,
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
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        title: "Gagal!",
                        text: response ? response.message : "Terjadi kesalahan.",
                        icon: "error"
                    });
                }
            });
        });

        // Trigger pencarian otomatis saat filter berubah
        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });

    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush


@endsection