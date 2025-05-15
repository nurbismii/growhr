@extends('layouts.app')

@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<style>
    /* Perbesar ukuran popover */
    .fc-popover {
        max-width: 400px !important;
        /* Atur lebar sesuai kebutuhan */
        width: auto;
    }
</style>
@endpush

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="text-primary">Kalender Kerja</h4>
    <hr>
    <div id='calendar'></div>
</div>

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

<script>
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";

        $('#calendar').fullCalendar({
            editable: false, // disable drag & drop
            selectable: false, // disable klik tanggal
            displayEventTime: false,
            eventLimit: true, // tampilkan "lebih banyak..." jika terlalu banyak event
            defaultView: 'month', // <-- tampil mingguan saat load
            height: 'auto',
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay' // <-- tombol untuk ganti view
            },

            events: {
                url: SITEURL + "/insight/kalender-kerja",
                type: "GET",
                error: function() {
                    alert("Gagal memuat data kalender.");
                }
            },

            // Optional: tampilkan tooltip saat hover
            eventRender: function(event, element) {
                element.css({
                    'color': '#fff'
                });
                element.attr('title', event.title);


            }
        });
    });
</script>

@endpush

@endsection