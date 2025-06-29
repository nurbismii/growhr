@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <div class="d-flex gap-2">
            <input type="radio" class="btn-check" name="divisi" id="all" value="" autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="all">Semua</label>

            @foreach($divisi as $d)
            <input type="radio" class="btn-check" name="divisi" id="divisi-{{ $d->id }}" value="{{ $d->id }}" autocomplete="off">
            <label class="btn btn-outline-primary" for="divisi-{{ $d->id }}">{{ $d->divisi }}</label>
            @endforeach
        </div>

        <div class="d-flex gap-2">
            <select id="status" class="form-select" style="width: auto;">
                <option value="progress">Progress</option>
                <option value="selesai">Selesai</option>
            </select>

            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text"><i class="bx bx-calendar text-primary"></i></span>
                <input type="text" name="tanggal_mulai" class="form-control daterange" />
            </div>

            <select id="limitSelect" class="form-select" style="width: auto;">
                <option value="6" {{ $limit == 6 ? 'selected' : '' }}>6</option>
                <option value="9" {{ $limit == 9 ? 'selected' : '' }}>9</option>
                <option value="12" {{ $limit == 12 ? 'selected' : '' }}>12</option>
                <option value="18" {{ $limit == 18 ? 'selected' : '' }}>18</option>
                <option value="24" {{ $limit == 24 ? 'selected' : '' }}>24</option>
            </select>
        </div>
    </div>
    <hr>
    <h5 class="text-center">PAPAN KERJA</h5>
    {{-- Container untuk di-replace --}}
    <div class="papan-kerja-container">
        @include('partials.papan-kerja', ['pekerjaan' => $pekerjaan])
    </div>
</div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        function getCurrentMonthRange() {
            let start = moment().startOf('month');
            let end = moment().endOf('month');
            return {
                start,
                end
            };
        }

        let {
            start,
            end
        } = getCurrentMonthRange();
        let currentStartDate = start.format('YYYY-MM-DD');
        let currentEndDate = end.format('YYYY-MM-DD');

        $('.daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        function loadFilteredData() {
            let status = $('#status').val();
            let limit = $('#limitSelect').val();
            let divisiId = $('input[name="divisi"]:checked').val();

            $.ajax({
                url: "{{ route('papan-kerja') }}",
                type: "GET",
                data: {
                    start_date: currentStartDate,
                    end_date: currentEndDate,
                    limit: limit,
                    divisi_id: divisiId,
                    status: status
                },
                success: function(response) {
                    $('.papan-kerja-container').html(response);
                }
            });
        }

        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            currentStartDate = picker.startDate.format('YYYY-MM-DD');
            currentEndDate = picker.endDate.format('YYYY-MM-DD');
            loadFilteredData();
        });

        $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            // Reset to current month if cancelled
            let range = getCurrentMonthRange();
            currentStartDate = range.start.format('YYYY-MM-DD');
            currentEndDate = range.end.format('YYYY-MM-DD');
            loadFilteredData();
        });

        // Limit berubah
        $('#limitSelect').on('change', function() {
            loadFilteredData();
        });

        // Status berubah
        $('#status').on('change', function() {
            loadFilteredData();
        });

        // Divisi berubah
        $('input[name="divisi"]').on('change', function() {
            loadFilteredData();
        });

        // Load awal
        loadFilteredData();
    });
</script>
@endpush