@extends('layouts.app')

@section('content')

@push('styles')
<style>
    .bg-purple {
        background-color: #9C4FFF;
        color: #fff;
    }

    .rounded-20 {
        border-radius: 20px;
    }

    .dashboard-card {
        background-color: rgb(162, 110, 229);
        color: #fff;
        padding: 25px 10px;
        text-align: center;
        border-radius: 20px;
        height: 100%;
        transition: 0.2s;
    }

    .dashboard-card:hover {
        box-shadow: 0 0 0 3px #9C4FFF;
    }

    .info-box {
        background-color: #eee;
        padding: 20px;
        border-radius: 12px;
    }

    .btn-outline-white {
        border: 1px solid #fff;
        color: #fff;
    }

    .btn-outline-white:hover {
        background-color: #fff;
        color: #9C4FFF;
    }
</style>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="bg-white p-4 rounded-4 shadow-sm">

        <!-- Header Purple Section -->
        <div class="bg-purple p-4 d-flex flex-column flex-md-row justify-content-between align-items-stretch rounded-20 position-relative overflow-hidden" style="min-height: 200px;">
            <div class="col-sm-7">
                <div class="card-body">
                    <h5 class="card-title text-white">Halo, {{ strtoupper(Auth::user()->name) }}! 🎉</h5>
                    <p class="mb-4">
                        Apa agenda pekerjaanmu hari ini? <br> Yuk catat agenda harian kamu
                    </p>
                    <a href="{{ route('log-harian.create') }}" class="btn btn-light btn-sm me-2 mb-3">
                        <i class="menu-icon tf-icons bx bx-plus-circle"></i> Buat Kegiatan
                    </a>
                    <a href="{{ route('log-harian.index') }}" class="btn btn-outline-white btn-sm mb-3">
                        <i class="menu-icon tf-icons bx bx-history"></i> Riwayat Kegiatan
                    </a>
                </div>
            </div>
            <div class="col-sm-5 d-flex align-items-end justify-content-center position-relative p-0" style="margin-bottom: -4px;">
                <!-- Gambar meja -->
                <img src="../assets/img/illustrations/meja.png"
                    alt="meja"
                    class="position-absolute"
                    style="bottom: 0; z-index: 1; height: 50px;">

                <!-- Gambar orang -->
                <img src="../assets/img/illustrations/man-with-laptop-light.png"
                    class="bottom-img position-relative"
                    alt="View Badge User"
                    height="180"
                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                    data-app-light-img="illustrations/man-with-laptop-light.png"
                    style="z-index: 2; margin-bottom: -22px;">
            </div>
        </div>

        <!-- Dashboard Menu -->
        <h5 class="fw-bold mt-4">Dashboard</h5>

        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('papan-kerja') }}">
                    <div class="dashboard-card">
                        <img src="{{ asset('dashboard-monitor.svg') }}" width="50" class="mb-2" alt="Papan Kerja">
                        <div class="fw-bold text-white">Papan Kerja</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('kalender-kerja') }}">
                    <div class="dashboard-card">
                        <img src="{{ asset('calendar-days.svg') }}" width="50" class="mb-2" alt="Kalender Kerja">
                        <div class="fw-bold">Kalender Kerja</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('tugas') }}">
                    <div class="dashboard-card border border-light">
                        <img src="{{ asset('task-checklist.svg') }}" width="50" class="mb-2" alt="Tugas">
                        <div class="fw-bold">Tugas</div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Informasi Penting -->
        <h5 class="fw-bold text-center mt-4">Informasi Penting</h5>
        <div class="info-box mt-2">
            <textarea id="informasi" class="form-control" rows="10" placeholder="Tulis informasi penting...">{{ Storage::exists('informasi-penting.txt') ? Storage::get('informasi-penting.txt') : '' }}</textarea>
        </div>
    </div>
</div>

@push('script')
<script>
    let timeout = null;
    $('#informasi').on('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("autosave.informasi") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    informasi: $('#informasi').val()
                },
                success: function(res) {
                    console.log('Tersimpan otomatis');
                }
            });
        }, 1000); // delay 1 detik setelah user berhenti ngetik
    });
</script>
@endpush


@endsection