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
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="card text-white bg-primary shadow-lg px-3 py-2" style="max-width: 22rem; height: 3rem;">
            <div class="card-body p-0">
                <h6 class="card-title text-white fw-bold m-2 text-center">Tugas</h6>
            </div>
        </div>
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
                <select name="prioritas[]" class="form-control select-prioritas w-100">
                    <option value="" disabled selected>Prioritas</option>
                    @foreach($prioritas as $prioritas)
                    <option value="{{ $prioritas->id }}">{{ $prioritas->prioritas }}</option>
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
            <table class="table" id="tugas">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center text-white">Tanggal Mulai</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Deskripsi Pekerjaan</th>
                        <th class="text-center text-white">Prioritas</th>
                        <th class="text-center text-white">Status Pekerjaan</th>
                        <th class="text-center text-white">Tanggal Update</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <canvas id="statusChart" height="150" class="mt-5"></canvas>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const statusMap = {
        'Belum Dimulai': 0,
        'Sedang Dikerjakan': 1,
        'Revisi': 2,
        'Laporan': 3,
        'Selesai': 4
    };

    let chartInstance;

    function getRandomColor() {
        const r = Math.floor(Math.random() * 200);
        const g = Math.floor(Math.random() * 200);
        const b = Math.floor(Math.random() * 200);
        return `rgb(${r}, ${g}, ${b})`;
    }

    function generateChart(riwayatData) {

        const groupedData = {};

        riwayatData.forEach(item => {
            const pekerjaan = item.pekerjaan;
            if (!pekerjaan || !pekerjaan.id) return;

            const pekerjaanId = pekerjaan.id;
            const pekerjaanName = pekerjaan.deskripsi_pekerjaan;

            if (!groupedData[pekerjaanId]) {
                groupedData[pekerjaanId] = {
                    label: pekerjaanName,
                    data: []
                };
            }

            groupedData[pekerjaanId].data.push({
                tgl: item.pembaruan.split(' ')[0],
                status: statusMap[item.status_pembaruan]
            });
        });

        const allDates = [...new Set(riwayatData.map(d => d.pembaruan.split(' ')[0]))].sort();

        const datasets = Object.values(groupedData).map(group => {
            const dataByDate = {};
            group.data.forEach(entry => {
                dataByDate[entry.tgl] = entry.status;
            });

            const filledData = allDates.map(tgl => dataByDate[tgl] ?? null);

            return {
                label: group.label,
                data: filledData,
                borderColor: getRandomColor(),
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 4,
                fill: false
            };
        });

        // destroy chart lama kalau ada
        if (chartInstance) chartInstance.destroy();

        const ctx = document.getElementById('statusChart').getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: allDates,
                datasets: datasets
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Object.keys(statusMap).find(k => statusMap[k] === value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // Initial load saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        let rawRiwayat = @json($riwayat_chart);
        const riwayatData = Array.isArray(rawRiwayat) ? rawRiwayat : [rawRiwayat];
        generateChart(riwayatData);
    });

    // Filter by PIC via AJAX
    document.getElementById('filter-pic').addEventListener('change', function() {
        const selectedPic = this.value;

        $.ajax({
            url: "{{ route('tugas') }}",
            method: "GET",
            data: {
                pic: selectedPic ? [selectedPic] : null
            },
            success: function(response) {
                console.log("Response dari server:", response);
                generateChart(response.riwayat);
            },
            error: function(err) {
                console.error("Gagal ambil data filter:", err);
            }
        });
    });
</script>

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

    $(document).ready(function() {
        let table = $('#tugas').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            scrollY: '60vh',
            scrollCollapse: true,
            "columnDefs": [{
                "className": "dt-center",
                "targets": "_all"
            }],
        });

        function fetchData() {
            let formData = $('#search-form').serialize();

            $.ajax({
                url: "{{ route('tugas') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    table.clear().draw();

                    response.riwayat.forEach(function(riwayat) {
                        table.row.add([
                            riwayat.pekerjaan?.tanggal_mulai ?? '-',
                            riwayat.pekerjaan?.get_user?.name ?? '-',
                            riwayat.pekerjaan?.deskripsi_pekerjaan ?? '-',
                            riwayat.pekerjaan?.get_prioritas?.prioritas ?? '-',
                            riwayat.status_pembaruan,
                            riwayat.pembaruan ?? '-'
                        ]).draw(false);
                    });

                    generateChart(response.riwayat);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Trigger pencarian otomatis saat filter berubah
        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });

        // Jalankan saat pertama kali halaman dimuat
        fetchData();
    });
</script>
@endpush

@endsection