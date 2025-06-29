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
            <div class="col-12 col-sm-6 col-md-4">
                <select name="pic[]" class="form-control select-pic w-100" id="select-pic">
                    <option value="" disabled selected>Person in Charge</option>
                    @foreach($user as $user)
                    <option value="{{ $user->id }}">{{ strtoupper($user->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-8">
                <select id="select-pekerjaan" name="pekerjaan_id[]" class="form-control select-pekerjaan" multiple>
                    <option value="" selected>Pekerjaan</option>
                </select>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table" id="tugas">
                <thead class="table-primary">
                    <tr>
                        <th></th>
                        <th class="text-center text-white">Tanggal Mulai</th>
                        <th class="text-center text-white">PIC</th>
                        <th class="text-center text-white">Deskripsi Pekerjaan</th>
                        <th class="text-center text-white">Status Pekerjaan</th>
                        <th class="text-center text-white">Tanggal Update</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <canvas id="chart" height="150" class="mt-5"></canvas>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
    $(document).ready(function() {
        let table = $('#tugas').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            scrollY: '60vh',
            scrollCollapse: true,
            columns: [{
                    data: null,
                    defaultContent: '<button class="btn btn-sm btn-outline-primary toggle-sub">+</button>',
                    orderable: false
                },
                {
                    data: 'pekerjaan.tanggal_mulai',
                    defaultContent: '-'
                },
                {
                    data: 'pekerjaan.get_user.name',
                    defaultContent: '-'
                },
                {
                    data: 'pekerjaan.deskripsi_pekerjaan',
                    defaultContent: '-'
                },
                {
                    data: 'status_pembaruan',
                    defaultContent: '-'
                },
                {
                    data: 'pembaruan',
                    defaultContent: '-'
                }
            ]
        });

        function fetchData() {
            let formData = $('#search-form').serializeArray();
            let hasFilter = false;

            // Cek apakah ada filter aktif
            formData.forEach(function(field) {
                if (field.value && field.value.trim() !== "") {
                    hasFilter = true;
                }
            });

            let ajaxData;
            if (hasFilter) {
                ajaxData = formData;
            } else {
                ajaxData = {
                    limit: 10,
                    order: 'desc'
                };
            }

            $.ajax({
                url: "{{ route('tugas') }}",
                type: "GET",
                data: ajaxData,
                success: function(response) {
                    table.clear();

                    window.lastRiwayat = response.riwayat;

                    let utama = response.riwayat.filter(r => !r.sub_pekerjaan_id);

                    utama.forEach(function(riwayat) {
                        table.row.add(riwayat);
                    });

                    table.draw();

                    // Di sini gunakan riwayat_chart yang sudah dibatasi backend
                    generateChart(response.riwayat_chart);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Expand tombol
        $('#tugas tbody').on('click', '.toggle-sub', function() {
            let tr = $(this).closest('tr');
            let row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                $(this).text('+');
            } else {
                let pekerjaanId = row.data().pekerjaan_id;
                let subs = window.lastRiwayat.filter(r => r.pekerjaan_id == pekerjaanId && r.sub_pekerjaan_id);

                if (subs.length === 0) {
                    row.child('<div class="text-muted">Tidak ada sub pekerjaan</div>').show();
                } else {
                    let html = '<table class="table table-sm table-bordered mb-0">';
                    html += '<thead><tr><th>Sub Pekerjaan</th><th>Status</th><th>Pembaruan</th></tr></thead><tbody>';
                    subs.forEach(s => {
                        // Pastikan akses ke sub_pekerjaan.sub_deskripsi_pekerjaan
                        let subDesc = s.sub_pekerjaan && s.sub_pekerjaan.sub_deskripsi_pekerjaan ? s.sub_pekerjaan.sub_deskripsi_pekerjaan : '-';
                        html += `<tr>
                <td>${subDesc}</td>
                <td>${s.status_pembaruan}</td>
                <td>${s.pembaruan}</td>
                </tr>`;
                    });
                    html += '</tbody></table>';

                    row.child(html).show();
                }
                $(this).text('-');
            }
        });

        // Trigger otomatis saat filter berubah
        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });

        fetchData();
    });

    const statusMap = {
        'Belum Dimulai': 0,
        'Sedang Dikerjakan': 1,
        'Revisi': 2,
        'Laporan': 3,
        'Selesai': 4,
        'Selesai Dan Diterima': 5
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
            const tglJam = item.pembaruan.substring(0, 16);
            const statusVal = statusMap[item.status_pembaruan];

            let groupKey = pekerjaanId;
            let labelName = pekerjaanName;

            if (item.sub_pekerjaan_id) {
                groupKey = pekerjaanId + '-' + item.sub_pekerjaan_id;
                labelName = `${pekerjaanName} - Sub ${item.sub_pekerjaan_id}`;
            }

            if (!groupedData[groupKey]) {
                groupedData[groupKey] = {
                    label: labelName,
                    data: []
                };
            }

            groupedData[groupKey].data.push({
                tgl: tglJam,
                status: statusVal
            });
        });

        const allDates = [...new Set(riwayatData.map(d => d.pembaruan.substring(0, 16)))].sort();

        const datasets = Object.values(groupedData).map(group => {
            const dataByDate = {};
            group.data.forEach(entry => {
                dataByDate[entry.tgl] = entry.status;
            });

            const filledData = allDates.map(tgl => dataByDate[tgl] ?? null);

            return {
                label: group.label,
                data: filledData,
                borderColor: group.label.includes('Sub') ?
                    getRandomColor() : 'black',
                borderWidth: group.label.includes('Sub') ? 1.5 : 3,
                tension: 0.3,
                pointRadius: 4,
                fill: false,
                spanGaps: true
            };
        });

        if (chartInstance) chartInstance.destroy();

        const ctx = document.getElementById('chart').getContext('2d');
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
                            callback: value => Object.keys(statusMap).find(k => statusMap[k] === value)
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const point = context.raw;
                                let statusDisplay = point && point.status_key ? point.status_key : Object.keys(statusMap).find(k => statusMap[k] === context.parsed.y);
                                return `${context.dataset.label}: ${statusDisplay}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Saat DOM siap, load chart awal
    document.addEventListener('DOMContentLoaded', function() {
        let rawRiwayat = @json($riwayat_chart);
        const riwayatData = Array.isArray(rawRiwayat) ? rawRiwayat : [rawRiwayat];
        generateChart(riwayatData);
    });

    $(document).ready(function() {

        $('.select-prioritas').select2({
            theme: 'bootstrap-5',
            placeholder: "Prioritas",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });

        $('.select-pic').select2({
            theme: 'bootstrap-5',
            placeholder: "PIC",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });

        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
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
</script>
@endpush

@endsection