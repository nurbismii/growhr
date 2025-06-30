@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold mb-4">Audit Trail</h4>

    <div class="card shadow rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="audit">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-white">#</th>
                            <th class="text-white">Event</th>
                            <th class="text-white">Tabel</th>
                            <th class="text-white">Item</th>
                            <th class="text-white">Perubahan</th>
                            <th class="text-white">User</th>
                            <th class="text-white">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $statusMap = [
                        1 => 'Belum Dimulai',
                        2 => 'Sedang Dikerjakan',
                        3 => 'Laporan',
                        4 => 'Revisi',
                        5 => 'Selesai',
                        7 => 'Selesai Dan Diterima'
                        ];
                        @endphp

                        @forelse ($audits as $audit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge 
                                        @if ($audit->event == 'created') bg-success
                                        @elseif ($audit->event == 'updated') bg-warning text-dark
                                        @elseif ($audit->event == 'deleted') bg-danger
                                        @else bg-secondary
                                        @endif">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>
                            <td>{{ $audit->auditable_type }}</td>
                            <td>
                                @if ($audit->auditable_type === App\Models\SubPekerjaan::class)
                                {{ $audit->auditable->sub_deskripsi_pekerjaan ?? '-' }}
                                <br>
                                <small class="text-muted">
                                    Utama : {{ $audit->auditable->deskripsi_pekerjaan ?? '-' }}
                                </small>
                                @elseif ($audit->auditable_type === App\Models\Pekerjaan::class)
                                {{ $audit->auditable->deskripsi_pekerjaan ?? '-' }}
                                <br>
                                <small class="text-muted">
                                    Pekerjaan Utama
                                </small>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#changes-{{ $audit->id }}">
                                    Lihat
                                </button>
                                <div class="collapse mt-2" id="changes-{{ $audit->id }}">
                                    <strong>Lama:</strong>
<pre class="bg-light p-2 rounded">
@php
$old = json_decode(json_encode($audit->old_values), true);
foreach ($old as $key => $val) {
    if ($key == 'status_pekerjaan_id') {
        $val = $statusMap[(int)$val] ?? $val;
    }
    echo json_encode([$key => $val], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
@endphp
</pre>

<strong>Baru:</strong>
<pre class="bg-light p-2 rounded">
@php
$new = json_decode(json_encode($audit->new_values), true);
foreach ($new as $key => $val) {
    if ($key == 'status_pekerjaan_id') {
        $val = $statusMap[(int)$val] ?? $val;
    }
    echo json_encode([$key => $val], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
@endphp
</pre>

                                </div>
                            </td>
                            <td>
                                {{ optional($audit->user)->name ?? 'System' }}
                            </td>
                            <td>
                                {{ $audit->created_at->format('d-m-Y H:i:s') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data audit.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        let table = $('#audit').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            scrollY: '60vh',
            scrollCollapse: true
        });
    });
</script>
@endpush

@endsection