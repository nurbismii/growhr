@extends('layouts.app')

@section('content')

@push('styles')
<style>
    .tooltip-inner {
        background-color: var(--bs-primary) !important;
        color: white !important;
        font-weight: bold;
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

    .slider-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input[type="range"] {
        width: 100%;
        accent-color: #696cff;
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
        background-color: #D3D3D3;
        /* Light gray color */
        color: #000;
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
</style>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-4 py-2" style="max-width: 22rem; height: 3.5rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Log Harian</h6>
            </div>
        </div>

        <a href="{{ route('log-harian.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Kegiatan
        </a>
    </div>

    <form id="search-form">
        <div class="row g-2 d-flex flex-wrap mb-3">
            @csrf
            <div class="col-12 col-sm-6 col-md-2">
                <select name="pekerjaan[]" class="form-control select-pekerjaan w-100">
                    <option value="" disabled selected>Pekerjaan</option>
                    @foreach($kategori_pekerjaan as $kp)
                    <option value="{{ $kp->id }}">{{ $kp->kategori_pekerjaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <select name="prioritas[]" class="form-control select-prioritas w-100">
                    <option value="" disabled selected>Prioritas</option>
                    @foreach($prioritas as $priorit)
                    <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
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
            <table class="table table-border" id="log-harian">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <td class="text-center"></td>
                        <th class="text-center">PIC</th>
                        <th class="text-center">Tanggal Pencatatan</th>
                        <th class="text-center">Sifat Pekerjaan</th>
                        <th class="text-center">Deskripsi Pekerjaan</th>
                        <th class="text-center">Prioritas</th>
                        <th class="text-center">Status Pekerjaan</th>
                        <th class="text-center">Kategori Pekerjaan</th>
                        <th class="text-center">Tanggal Mulai</th>
                        <th class="text-center">Tanggal Selesai</th>
                        <th class="text-center">Durasi Pekerjaan</th>
                        <th class="text-center">Deadline</th>
                        <th class="text-center">Penanggung Jawab</th>
                        <th class="text-center">Tingkat Kesulitan</th>
                        <th class="text-center">Alasan Kesulitan</th>
                        <th class="text-center">Lampiran</th>
                        <th class="text-center">Feedback Atasan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pekerjaan as $kerjaan)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary toggle-btn" data-id="{{$kerjaan->id}}">+</button>
                        </td>
                        <td>{{ $kerjaan->getUser ->name }}</td>
                        <td>{{ date_format($kerjaan->created_at, 'Y-m-d') }}</td>
                        <td>{{ $kerjaan->getSifatPekerjaan->pekerjaan }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $kerjaan->deskripsi_pekerjaan }}">
                                {{ substr($kerjaan->deskripsi_pekerjaan, 0, 25) }}...
                            </span>
                        </td>
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
                        <td>{{ $kerjaan->tanggal_mulai }}</td>
                        <td class="tanggal-selesai">{{ $kerjaan->tanggal_selesai }}</td>
                        <td>{{ $kerjaan->durasi }}</td>
                        <td class="deadline"></td>
                        <td>{{ $kerjaan->getPjPekerjaan->name }}</td>
                        <td>{{ $kerjaan->tingkat_kesulitan }}/10</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $kerjaan->deskripsi_pekerjaan }}">
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
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#main-edit{{$kerjaan->id}}"><i class="bx text-primary bx-edit-alt me-2"></i> Edit</a>
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

<!-- Modal edit -->.
@include('log-harian.modal.modal-edit')
<!-- Modal edit end -->

<!-- Sub Modal edit -->.
@foreach($pekerjaan as $pk)
@foreach($pk->getSubPekerjaan as $subpk)
<div class="modal fade" id="sub-edit{{$subpk->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Sub Pekerjaan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('log-harian.update.sub', $subpk->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Pekerjaan</button>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalPelaporan" value="{{ date_format($subpk->created_at, 'd/m/Y') }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sifatPekerjaan" class="form-label">Sifat Pekerjaan</label>
                            <input type="text" class="form-control" name="sifat_pekerjaan" readonly>
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
                                @foreach($user_modal as $um)
                                <option value="{{ $um->id }}">{{ $um->name }}</option>
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
                            <textarea class="form-control" name="deskripsi_pekerjaan" id="deskripsiTugas" placeholder="Isi Tugas" rows="3" required readonly>{{ $pk->deskripsi_pekerjaan }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" value="{{ $subpk->tanggal_mulai }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" value="{{ $subpk->tanggal_selesai }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Durasi</label>
                            <input type="text" class="form-control duration" name="durasi" id="duration" value="{{ $subpk->durasi }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="text" class="form-control deadline" name="deadline" id="deadline" value="{{ $subpk->deadline }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-secondary">
                                Tingkat Kesulitan <span class="text-danger">*</span>
                            </label>
                            <div class="slider-container">
                                <span>1</span>
                                <div class="slider-wrapper">
                                    <span class="slider-value">{{ $subpk->tingkat_kesulitan }}</span>
                                    <input type="range" name="tingkat_kesulitan" class="slider-range" min="1" max="10" step="1" value="{{ $subpk->tingkat_kesulitan }}" required>
                                </div>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="alasanPemilihan" class="form-label">Alasan pemilihan tingkat kesulitan</label>
                            <input type="text" class="form-control" name="alasan" id="alasanPemilihan" value="{{ $subpk->alasan }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Dokumen (Opsional)</label>
                                <div>
                                    <label for="fileInput{{$subpk->id}}" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="ms-2 fileLabel" id="fileLabel{{$subpk->id}}">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInput{{$subpk->id}}" class="fileInput" name="lampiran">
                                </div>
                                <div class="file-name fileName{{$subpk->id}}"></div>
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
@endforeach
<!-- Sub Modal edit end -->

@push('script')
<script>
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
                    select.classList.add("text-success", "bg-label-success");
                    break;
                case "4":
                    select.classList.add("text-secondary", "bg-label-secondary");
                    break;
                case "5":
                    select.classList.add("text-warning", "bg-label-warning");
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
        let today = new Date(); // Ambil tanggal hari ini

        document.querySelectorAll("tr").forEach(row => {
            let cellTanggal = row.querySelector(".tanggal-selesai"); // Ambil elemen tanggal selesai
            let selectStatus = row.querySelector(".status-pekerjaan"); // Ambil elemen select status pekerjaan

            if (!cellTanggal || !selectStatus) return; // Jika tidak ada, lewati

            let statusPekerjaan = selectStatus.options[selectStatus.selectedIndex].text.trim().toLowerCase();
            if (statusPekerjaan === "selesai") {
                row.classList.remove("table-danger", "table-warning", "table-success"); // Hapus semua warna jika selesai
                return; // Tidak lanjutkan pengecekan warna
            }

            let tanggalSelesai = new Date(cellTanggal.innerText.trim());
            let selisihHari = Math.ceil((tanggalSelesai - today) / (1000 * 60 * 60 * 24));

            // Reset semua warna
            row.classList.remove("table-danger", "table-warning", "table-success");

            if (selisihHari <= 0) {
                row.classList.add("table-danger"); // Lewat deadline
            } else if (selisihHari <= 1) {
                row.classList.add("table-warning"); // 1 hari sebelum deadline
            } else if (selisihHari <= 3) {
                row.classList.add("table-success"); // 3 hari sebelum deadline
            }
        });
    }

    // Jalankan fungsi saat halaman dimuat
    document.addEventListener("DOMContentLoaded", updateRowColors);

    // Jalankan lagi jika data diperbarui via AJAX
    $(document).on('xhr.dt', function() {
        setTimeout(updateRowColors, 500); // Tunggu data baru di-load
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

    $(document).ready(function() {
        let table = $('#log-harian').DataTable({
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
                url: "{{ route('log-harian.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    table.clear().draw();

                    response.pekerjaan.forEach(function(kerjaan, index) {

                        let statusOptions = response.status_pekerjaan.map(sp =>
                            `<option value="${sp.id}" ${kerjaan.get_status_pekerjaan.id === sp.id ? "selected" : ""}>${sp.status_pekerjaan}</option>`
                        ).join("");

                        table.row.add([
                            index + 1,
                            '<button class="btn btn-sm btn-primary toggle-btn" data-id="' + kerjaan.id + '">+</button>',
                            kerjaan.get_user.name,
                            formatTimestamp(kerjaan.created_at),
                            kerjaan.get_sifat_pekerjaan.pekerjaan,
                            `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(kerjaan.deskripsi_pekerjaan)}">
                                ${escapeHtml(kerjaan.deskripsi_pekerjaan.substring(0, 25))}...
                            </span>`,
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
                                <i class="bx bx-link-alt me-1"></i> ${kerjaan.lampiran}
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
                            '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#main-edit' + kerjaan.id + '">' +
                            '<i class="bx text-primary bx-edit-alt me-2"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item delete-btn" href="' + "{{ route('log-harian.destroy', ':id') }}".replace(':id', kerjaan.id) + '" data-confirm-delete="true">' +
                            '<i class="bx text-primary bx-trash me-2"></i> Delete' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ]).draw();
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

        // Update sub status pekerjaan 
        $('#log-harian tbody').on('change', '.sub-status', function() {
            let newStatusId = $(this).val(); // Ambil ID status yang dipilih
            let subPekerjaanId = $(this).data('id');

            console.log(newStatusId);
            console.log(subPekerjaanId);

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
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat memperbarui status sub pekerjaan.",
                        icon: "error",
                        confirmButtonText: "Coba Lagi"
                    });
                    console.error("Gagal memperbarui status sub pekerjaan:", xhr.responseText);
                }
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

            // Cek apakah sub-row sudah ada
            if ($(tr).next().hasClass('sub-row')) {
                // Jika sudah ada, hapus semua sub-row terkait
                $(tr).nextAll('.sub-row').remove();
                $(this).html('+').removeClass('btn-secondary').addClass('btn-primary');
            } else {
                // Hapus semua sub-row yang terbuka sebelumnya
                $('.sub-row').remove();
                $('.toggle-btn').removeClass('btn-secondary').addClass('btn-primary').html('+');

                // Ambil data sub-pekerjaan melalui AJAX
                $.ajax({
                    url: "/log-harian/sub/" + id,
                    type: "GET",
                    success: function(subPekerjaan) {
                        let subRows = subPekerjaan.map((sub, index) => `
                    <tr class="sub-row table-hover">
                        <td></td>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(sub.get_user.name)}</td>
                        <td>${formatTimestamp(sub.created_at)}</td>
                        <td>${escapeHtml(sub.get_sifat_pekerjaan.pekerjaan)}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="${escapeHtml(sub.deskripsi_pekerjaan)}">
                                ${escapeHtml(sub.deskripsi_pekerjaan.substring(0, 25))}...
                            </span>
                        </td>
                        <td>${escapeHtml(sub.get_prioritas.prioritas)}</td>
                        <td>
                            <select class="form-select status-pekerjaan  form-select-sm sub-status" data-id="${sub.id}">
                                <option value="${sub.get_status_pekerjaan.id}" selected>${escapeHtml(sub.get_status_pekerjaan.status_pekerjaan)}</option>
                                ${statusOptions}
                            </select>
                        </td>
                        <td>${escapeHtml(sub.get_kategori_pekerjaan.kategori_pekerjaan)}</td>
                        <td>${escapeHtml(sub.tanggal_mulai)}</td>
                        <td class="tanggal-selesai">${escapeHtml(sub.tanggal_selesai)}</td>
                        <td>${escapeHtml(sub.durasi)}</td>
                        <td class"deadline">${escapeHtml(sub.deadline)}</td>
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
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-edit${sub.id}">
                                        <i class="bx text-primary bx-edit-alt me-2"></i> Edit
                                    </a>
                                    <a class="dropdown-item delete-btn" href="${"{{ route('log-harian.destroy.sub', ':id') }}".replace(':id', sub.id)}" data-confirm-delete="true">
                                        <i class="bx text-primary  bx-trash me-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `).join('');

                        // Tambahkan sub-row setelah baris utama
                        $(tr).after(subRows);
                        $(tr).find('.toggle-btn').html('-').removeClass('btn-primary').addClass('btn-primary');
                        updateDeadline(); //

                        setTimeout(updateRowColors, 100);

                        setTimeout(() => {
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        }, 100);

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

    document.querySelectorAll(".slider-container").forEach(container => {
        const slider = container.querySelector(".slider-range");
        const sliderValue = container.querySelector(".slider-value");

        function updateValuePosition() {
            const percent = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
            sliderValue.style.left = `calc(${percent}% - 5px)`;
            sliderValue.textContent = slider.value;
        }

        slider.addEventListener("input", updateValuePosition);

        // Set nilai default
        slider.value = 8;
        updateValuePosition();
    });


    $(document).on("change", ".fileInput", function() {
        let fileId = $(this).attr("id"); // Dapatkan ID input file (misal: fileInput1)
        let fileName = this.files[0] ? this.files[0].name : "Pilih File";

        // Ubah label & nama file berdasarkan ID yang sesuai
        $("#fileLabel" + fileId.replace("fileInput", "")).text(fileName);
        $("#fileName" + fileId.replace("fileInput", "")).text(fileName);
    });

    document.addEventListener("DOMContentLoaded", function() {
        function calculateDays(startInput, endInput, durationInput, deadlineInput) {
            const startDate = new Date(startInput.value);
            const endDate = new Date(endInput.value);

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
                const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
                durationInput.value = daysDiff + " Hari";
                deadlineInput.value = daysDiff > 0 ? "H-" + daysDiff : "H-0";
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

    // Fungsi untuk mengupdate deadline di tabel utama dan sub-tabel
    function updateDeadline() {
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset waktu agar lebih akurat

        // Update deadline di tabel utama
        document.querySelectorAll("#log-harian tbody tr").forEach(row => {
            let cellTanggalSelesai = row.querySelector("td:nth-child(11)"); // Tanggal Selesai
            let cellDeadline = row.querySelector("td:nth-child(13)"); // Deadline

            if (!cellTanggalSelesai || !cellDeadline) return;

            let tanggalSelesai = new Date(cellTanggalSelesai.innerText.trim());
            tanggalSelesai.setHours(0, 0, 0, 0);

            let selisihHari = Math.ceil((tanggalSelesai - today) / (1000 * 60 * 60 * 24));

            // Jika selisih -1 atau lebih kecil, ubah menjadi 0 (H-0)
            if (selisihHari <= -1) {
                selisihHari = 0;
            }

            // Update nilai deadline
            cellDeadline.innerText = `H-${selisihHari}`;
        });

        // Update deadline di sub-tabel
        document.querySelectorAll("#log-harian tbody tr.sub-row").forEach(row => {
            let cellTanggalSelesai = row.querySelector("td:nth-child(11)"); // Tanggal Selesai sub
            let cellDeadline = row.querySelector("td:nth-child(13)"); // Deadline sub

            if (!cellTanggalSelesai || !cellDeadline) return;

            let tanggalSelesai = new Date(cellTanggalSelesai.innerText.trim());
            tanggalSelesai.setHours(0, 0, 0, 0);

            let selisihHari = Math.ceil((tanggalSelesai - today) / (1000 * 60 * 60 * 24));

            // Jika selisih -1 atau lebih kecil, ubah menjadi 0 (H-0)
            if (selisihHari <= -1) {
                selisihHari = 0;
            }

            // Update nilai deadline
            cellDeadline.innerText = `H-${selisihHari}`;
        });
    }
</script>
@endpush

@endsection