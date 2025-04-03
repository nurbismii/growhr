@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <button class="btn btn-primary btn-lg mb-2">Kategori Pelayanan</button>
    <a href="{{ route('kategori-pelayanan.create') }}" class="btn btn-primary text-white float-end">
        <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Pelayanan
    </a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-primary">
                    <tr>
                        <th class="text-white">No</th>
                        <th class="text-white">Pelayanan</th>
                        <th class="text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($kategori_pelayanan as $kp)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $kp->pelayanan }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('kategori-pelayanan.edit', $kp->id) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('kategori-pelayanan.destroy', $kp->id) }}" data-confirm-delete="true"><i class="bx bx-trash me-2"></i> Delete</a>
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
<!--/ Bootstrap Dark Table -->
@endsection