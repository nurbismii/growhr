@extends('layouts.app')

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

    #laporan-hasil {
        table-layout: fixed;
        width: 100%;
    }

    #laporan-hasil tbody tr {
        background: none !important;
    }

    #laporan-hasil td:first-child,
    #laporan-hasil th:first-child {
        width: 50px;
        /* Atur lebar yang sama */
        text-align: center;
    }

    #laporan-hasil th,
    #laporan-hasil td {
        text-align: center;
        white-space: nowrap;
    }
</style>

<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Laporan Hasil</h6>
            </div>
        </div>
        <a href="{{ route('laporan-hasil.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Hasil
        </a>
    </div>

    <form id="search-form">
        <div class="row g-2 d-flex flex-wrap mb-3">
            @csrf
            <div class="col-12 col-sm-6 col-md-3">
                <select id="pekerjaan_id" name="pekerjaan_id[]" class="form-control select-pekerjaan">
                    <option value="" disabled selected>Pekerjaan</option>
                    @foreach($pekerjaan as $pekerjaan)
                    <option value="{{ $pekerjaan->id }}">{{ $pekerjaan->deskripsi_pekerjaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="status_laporan[]" class="form-control select-prioritas w-100">
                    <option value="" disabled selected>Prioritas</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="revisi">Revisi</option>
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
            <table class="table" id="laporan-hasil">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center text-white">No</th>
                        <th class="text-center text-white">Tanggal Pelaporan</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Deskripsi Pekerjaan</th>
                        <th class="text-center text-white">File</th>
                        <th class="text-center text-white">Status Pelaporan</th>
                        <th class="text-center text-white">Divisi</th>
                        <th class="text-center text-white">Keterangan</th>
                        <th class="text-center text-white">Feedback</th>
                        <th class="text-center text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil as $hasil)

                    @php

                    if(Auth::user()->role == 'ASMEN') {
                    $statusOptions = ['ditolak', 'disetujui'];
                    } else {
                    $statusOptions = ['diajukan', 'revisi'];
                    }
                    $selectedStatus = $hasil->status_laporan;
                    $filteredOptions = array_diff($statusOptions, [$selectedStatus]); // Hapus yang sudah ada
                    @endphp

                    <tr data-id="{{ $hasil->id }}">
                        <td>{{ ++$no }}</td>
                        <td>{{ date('d-m-Y', strtotime(($hasil->created_at))) }}</td>
                        <td>{{ $hasil->pic->name }}</td>
                        <td>{{ $hasil->pekerjaan != null ? $hasil->pekerjaan->deskripsi_pekerjaan : '-'}}</td>
                        <td>
                            <a class="nav-link" target="_blank" href="{{ asset('Laporan Hasil/' . $hasil->pic->name  . '/' . $hasil->doc_laporan) }}">
                                <i class="bx bx-link-alt me-1"></i> {{ $hasil->doc_laporan ?? '---' }}
                            </a>
                        </td>
                        <td>
                            <select class="form-select form-select-sm main-status status-pekerjaan">
                                <option value="{{ $selectedStatus }}">{{ ucfirst($selectedStatus) }}</option> <!-- Tampilkan yang dipilih -->
                                @foreach ($filteredOptions as $option)
                                <option option value="{{ $option }}">{{ ucfirst($option) }}</option> <!-- Hanya opsi yang belum ditampilkan -->
                                @endforeach
                            </select>
                        </td>
                        <td>{{ optional($hasil->pic)->getNameDivisi() ?? '--' }}</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#keteranganModal{{ $hasil->id }}">
                                Detail Keterangan
                            </a>
                        </td>
                        <td>{!! substr(strip_tags($hasil->feedback_atasan), 0, 25) !!}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit text-primary"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if(Auth::user()->role == 'ASMEN')
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#feeback{{$hasil->id}}" data-id="{{ $hasil->id }}" data-keterangan="{{ $hasil->keterangan }}"><i class="bx text-primary btn-edit bx-plus-circle me-2"></i> Feedback </a>
                                    @endif
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit{{$hasil->id}}" data-id="{{ $hasil->id }}" data-keterangan="{{ $hasil->keterangan }}"><i class="bx text-primary btn-edit bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('laporan-hasil.destroy', $hasil->id) }}" data-confirm-delete="true"><i class="bx text-primary bx-trash me-2"></i> Delete</a>
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

@foreach($modal_hasil as $m_hasil)
<!-- Modal Detail -->
<div class="modal fade" id="keteranganModal{{ $m_hasil->id }}" tabindex="-1" aria-labelledby="keteranganModal{{ $m_hasil->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white d-flex align-items-center gap-0">
                    <i class="bx bx-file"></i> Detail Laporan ID #{{ $m_hasil->id }}-{{ date('mY', strtotime($m_hasil->created_at)) }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body px-4 py-3 text-center">
                <div class="row g-3 mb-3 justify-content-center">
                    <div class="col-md-4">
                        <i class="bx bx-calendar"></i>
                        <strong>Tanggal Pelaporan :</strong><br>
                        {{ date('d-m-Y', strtotime($m_hasil->created_at)) }}
                    </div>
                    <div class="col-md-4">
                        <i class="bx bx-user"></i>
                        <strong>PIC :</strong><br>
                        {{ $m_hasil->pic->name }}
                    </div>
                    <div class="col-md-4">
                        <i class="bx bx-briefcase"></i>
                        <strong>Pekerjaan :</strong><br>
                        {{ $m_hasil->pekerjaan->deskripsi_pekerjaan ?? '-' }}
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <i class="bx bx-file"></i>
                        <strong>Dokumen :</strong>
                        @if ($m_hasil->doc_laporan)
                        <a href="{{ asset('Laporan Hasil/' . $m_hasil->pic->name  . '/' . $m_hasil->doc_laporan) }}"
                            target="_blank"
                            class="text-decoration-none text-primary fw-semibold">
                            <i class="bx bx-link-alt"></i> {{ $m_hasil->doc_laporan }}
                        </a>
                        @else
                        <span class="text-muted">---</span>
                        @endif
                    </div>
                    <div class="mt-2 mt-md-0">
                        <i class="bx bx-check-circle"></i>
                        <strong>Status Laporan :</strong>
                        <span class="badge bg-success">{{ ucfirst($selectedStatus ?? '') }}</span>
                    </div>
                </div>

                <div class="text-start">
                    <h6 class="d-flex align-items-center gap-2">
                        <i class="bx bx-message-rounded-dots"></i> Keterangan :
                    </h6>
                    <div class="border rounded p-3 bg-light mb-3">
                        {!! $m_hasil->keterangan !!}
                    </div>

                    <h6 class="d-flex align-items-center gap-2">
                        <i class="bx bx-comment-dots"></i> Feedback :
                    </h6>
                    <div class="border rounded p-3 bg-light mb-3">
                        {!! $m_hasil->feedback_atasan ?? 'Belum ada feedback.' !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span class="text-muted small">Ditampilkan pada: {{ now()->format('d-m-Y H:i') }}</span>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Tambah feedback modal laporan hasil -->
@foreach($modal_hasil as $m_hasil)

<div class="modal fade" id="feeback{{$m_hasil->id}}" tabindex="-1" aria-labelledby="feeback{{$m_hasil->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="feeback{{$m_hasil->id}}">Tambahkan Feedback</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan-hasil.store.feedback', $m_hasil->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="row mb-4">
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Feedback
                                <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" class="feedback-old" name="feedback" value="{{ $m_hasil->feedback_atasan }}">
                            <div id="editor-feedback-{{$m_hasil->id}}"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white float-end mt-5">
                        Kirim &nbsp;<span class="tf-icons bx bx-send"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- Tambah feedback modal laporan hasil end -->

<!-- Edit modal laporan hasil -->
@foreach($modal_hasil as $m_hasil)

@php
$statusOptions = ['diajukan', 'revisi'];
$selectedStatus = $m_hasil->status_laporan;
$filteredOptions = array_diff($statusOptions, [$selectedStatus]); // Hapus yang sudah ada
@endphp

<div class="modal fade" id="edit{{$m_hasil->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Laporan Hasil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan-hasil.update', $m_hasil->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalPelaporan" value="{{ date('Y/m/d', strtotime($m_hasil->created_at)) }}" readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jenisKegiatan" class="form-label">Deskripsi Pekerjaan</label>
                            <input type="text" name="pekerjaan_id" class="form-control" id="jenisKegiatan" value="{{ $m_hasil->pekerjaan->deskripsi_pekerjaan }}" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="statusLaporan" class="form-label">Status Laporan</label>
                            <select id="statusLaporan" name="status_laporan" class="form-select" id="statusLaporan" required>
                                <option value="{{ $selectedStatus }}">{{ ucfirst($selectedStatus) }}</option> <!-- Tampilkan yang dipilih -->
                                @foreach ($filteredOptions as $option)
                                <option option value="{{ $option }}">{{ ucfirst($option) }}</option> <!-- Hanya opsi yang belum ditampilkan -->
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="id" value="{{ $m_hasil->id }}">
                        <input type="hidden" name="keterangan" id="keterangan-input-{{ $m_hasil->id }}" value="{{ $m_hasil->keterangan }}">

                        <div class="col-md-12 mb-5">
                            <label class="form-label">Keterangan</label>
                            <div id="editor-{{ $m_hasil->id }}" class="editor" style="min-height: 160px;">{!! $m_hasil->keterangan !!}</div>
                        </div>

                        <div class="col-md-12 mb-3 mt-5">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Dokumen Laporan</label>
                                <div>
                                    <label for="fileInputEditLaporan{{$m_hasil->id}}" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="ms-2 fileLabel" id="fileLabelEditLaporan{{$m_hasil->id}}">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInputEditLaporan{{$m_hasil->id}}" class="fileInputEditLaporan" name="doc_laporan">
                                </div>
                                <div class="file-name fileInputEditLaporan{{$m_hasil->id}}"></div>
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
<!-- Edit modal laporan hasil end -->

@push('script')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
    $(document).on("change", ".fileInputEditLaporan", function() {
        let fileId = $(this).attr("id"); // Dapatkan ID input file (misal: fileInput1)
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";

        // Ubah label & nama file berdasarkan ID yang sesuai
        $("#fileLabelEditLaporan" + fileId.replace("fileInputEditLaporan", "")).text(fileName);
        $("#fileNameEdit" + fileId.replace("fileInputEditLaporan", "")).text(fileName);
    });

    document.addEventListener("DOMContentLoaded", function() {
        function setTanggalPelaporan() {
            let today = new Date().toISOString().split('T')[0];

            document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
                input.value = today;
            });
        }

        // Jalankan saat halaman dimuat
        setTanggalPelaporan();

        // Pantau perubahan dalam modal jika elemen muncul setelah AJAX
        let observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === "childList") {
                    setTanggalPelaporan(); // Setel ulang jika ada elemen baru
                }
            });
        });

        // Pantau perubahan dalam seluruh dokumen atau modal tertentu
        observer.observe(document.body, {
            childList: true,
            subtree: true
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

    document.addEventListener("DOMContentLoaded", function() {
        // Fungsi untuk memperbarui warna select berdasarkan nilai yang dipilih
        window.updateSelectColor = function(select) {
            let selectedValue = select.value;

            // Reset semua class warna
            select.classList.remove(
                "text-primary", "text-success", "text-warning", "text-danger",
                "bg-label-success", "bg-label-warning", "bg-label-primary", "bg-label-danger",
            );

            // Tambahkan class warna sesuai status
            switch (selectedValue) {
                case "diajukan":
                    select.classList.add("text-primary", "bg-label-primary");
                    break;
                case "disetujui":
                    select.classList.add("text-success", "bg-label-success");
                    break;
                case "revisi":
                    select.classList.add("text-warning", "bg-label-warning");
                    break;
                case "ditolak":
                    select.classList.add("text-danger", "bg-label-danger");
                    break;
            }
        };

        // Inisialisasi warna untuk semua elemen yang sudah ada di halaman
        document.querySelectorAll(".main-status").forEach(updateSelectColor);

        // Event listener untuk perubahan status (gunakan event delegation)
        document.addEventListener("change", function(event) {
            if (event.target.matches(".main-status")) {
                updateSelectColor(event.target);
            }
        });

        // Pastikan warna diperbarui setelah DataTables reload
        $('#laporan-hasil').on('draw.dt', function() {
            document.querySelectorAll(".main-status").forEach(updateSelectColor);
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];

        // Pilih semua elemen dengan class 'tanggalPelaporan' (gunakan class untuk multiple elements)
        document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
            input.value = today;
        });
    });

    $(document).ready(function() {
        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });
    });

    $(document).ready(function() {
        $('.select-prioritas').select2({
            theme: 'bootstrap-5',
            placeholder: "Prioritas",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });
    });

    $(document).ready(function() {
        $('.select-pic').select2({
            theme: 'bootstrap-5',
            placeholder: "PIC",
            allowClear: true, // Memungkinkan pengguna menghapus pilihan
            width: '100%'
        });
    });

    function getBasename(path) {
        return path.split('/').pop();
    }

    function stripHtml(html) {
        const div = document.createElement("div");
        div.innerHTML = html;
        return div.textContent || div.innerText || "";
    }

    $(document).ready(function() {
        let table = $('#laporan-hasil').DataTable({
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
                url: "{{ route('laporan-hasil.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    table.clear().draw();

                    console.log(response);

                    response.hasil.forEach(function(hasil, index) {
                        let statusOptions = response.status_laporan.map(status =>
                            `<option value="${status}" ${hasil.status_laporan === status ? "selected" : ""}>${ucfirst(status)}</option>`
                        ).join("");

                        table.row.add([
                            index + 1,
                            formatTimestamp(hasil.created_at),
                            escapeHtml(hasil.pic_name),
                            escapeHtml(hasil.pekerjaan_deskripsi),
                            hasil.doc_laporan ?
                            `<a class="nav-link" target="_blank" href="/Laporan Hasil/${hasil.pic_name}/${hasil.doc_laporan}">
                            <i class="bx bx-link-alt me-1"></i> ${hasil.doc_laporan}
                             </a>` :
                            '---',
                            `<select class="form-select form-select-sm main-status status-pekerjaan">
                             ${statusOptions}
                            </select>`,
                            hasil.pic_divisi || '-',
                            `<a href="#" data-bs-toggle="modal" data-bs-target="#keteranganModal${hasil.id}">
                            Detail Keterangan
                            </a>`,
                            hasil.feedback ? escapeHtml(hasil.feedback) : '---',
                            '<div class="dropdown">' +
                            '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                            '<i class="bx bx-edit text-primary"></i>' +
                            '</button>' +
                            '<div class="dropdown-menu">' +
                            '<a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit' + hasil.id + '">' +
                            '<i class="bx text-primary bx-edit-alt me-2"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item delete-btn" href="' + "{{ route('laporan-hasil.destroy', ':id') }}".replace(':id', hasil.id) + '" data-confirm-delete="true">' +
                            '<i class="bx text-primary bx-trash me-2"></i> Delete' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ]).draw().node().setAttribute('data-id', hasil.id);
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

        // Update status laporan
        $('#laporan-hasil tbody').on('change', '.main-status', function() {
            let newStatus = $(this).val(); // Ambil status yang dipilih
            let hasilId = $(this).closest('tr').data('id'); // Ambil ID laporan hasil

            if (!hasilId) {
                Swal.fire({
                    title: "Error!",
                    text: "ID laporan tidak ditemukan!",
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

            // Kirim update status ke backend
            $.ajax({
                url: "/laporan-hasil/update-status-laporan/" + hasilId,
                type: "POST",
                data: {
                    status_laporan: newStatus
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: response.message,
                        icon: "success",
                        showConfirmButton: true
                    });

                    // Update warna select setelah perubahan status
                    updateSelectColor($(`tr[data-id="${hasilId}"] .main-status`)[0]);
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

    // Inisialisasi Quill editor hanya saat modal feedback dibuka, gunakan id unik untuk setiap editor
    $(document).on('shown.bs.modal', function(event) {
        const modal = $(event.target);
        // Cek apakah modal yang dibuka adalah modal feedback
        if (modal.attr('id') && modal.attr('id').startsWith('feeback')) {
            // Ambil id laporan hasil dari id modal, misal: feeback12 -> 12
            const hasilId = modal.attr('id').replace('feeback', '');
            const editorSelector = '#editor-feedback-' + hasilId;
            const editorDiv = modal.find(editorSelector);
            if (editorDiv.length && !editorDiv.data('quill-initialized')) {
                const quill = new Quill(editorSelector, {
                    theme: 'snow'
                });
                editorDiv.data('quill-initialized', true);

                // Ambil isi feedback dari input hidden .feedback-old
                const feedbackValue = modal.find('.feedback-old').val() || '';
                quill.root.innerHTML = feedbackValue;

                // Sync Quill content ke input hidden sebelum submit
                modal.find('form').on('submit', function() {
                    const html = quill.root.innerHTML;
                    $(this).find('input[name="feedback"]').val(html);
                });
            }
        }
    });

</script>
@endpush
<!--/ Bootstrap Dark Table -->
@endsection