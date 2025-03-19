@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <button class="btn btn-primary btn-lg mb-2">Prioritas</button>
    <a href="{{ route('prioritas.create') }}" class="btn btn-primary text-white float-end">
        <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Prioritas
    </a>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($prioritas as $priorit)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $priorit->prioritas }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('prioritas.edit', $priorit->id) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="{{ route('prioritas.destroy', $priorit->id) }}" data-confirm-delete="true"><i class="bx bx-trash me-2"></i> Delete</a>
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