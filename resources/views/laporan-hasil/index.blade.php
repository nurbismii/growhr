@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <button class="btn btn-primary btn-lg mb-2">Laporan Hasil</button>
    <a href="{{ route('laporan-hasil.create') }}" class="btn btn-primary text-white float-end">
        <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Hasil
    </a>

    <!-- Basic Bootstrap Table -->
    <div class="row mb-3">
        <div class="col-md-3 mb-3">
            <input class="form-control" type="search" placeholder="Search....">
        </div>
        <div class="col-md-2 mb-3">
            <select id="defaultSelect" class="form-select">
                <option>PIC</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <select id="defaultSelect" class="form-select">
                <option>Prioritas</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <input class="form-control" type="month">
        </div>

        <div class="col-md-3">
            <button type="button" class="btn btn-outline-primary float-end">
                <span class="tf-icons bx bx-search"></span>&nbsp; Hasil
            </button>
        </div>
    </div>


    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Pekerjaan</th>
                        <th>PIC</th>
                        <th>Catatan</th>
                        <th>Lampiran</th>
                        <th>Feedback</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td>1</td>
                        <td>05-03-2025</td>
                        <td>
                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                <li
                                    data-bs-toggle="tooltip"
                                    data-popup="tooltip-custom"
                                    data-bs-placement="top"
                                    class="avatar avatar-xs pull-up"
                                    title="Lilian Fuller">
                                    <img src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                                </li>
                                <li
                                    data-bs-toggle="tooltip"
                                    data-popup="tooltip-custom"
                                    data-bs-placement="top"
                                    class="avatar avatar-xs pull-up"
                                    title="Sophia Wilkerson">
                                    <img src="../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle" />
                                </li>
                                <li
                                    data-bs-toggle="tooltip"
                                    data-popup="tooltip-custom"
                                    data-bs-placement="top"
                                    class="avatar avatar-xs pull-up"
                                    title="Christina Parker">
                                    <img src="../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle" />
                                </li>
                            </ul>
                        </td>
                        <td>Konseling</td>
                        <td>Konseling</td>
                        <td>Solution</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Bootstrap Dark Table -->
@endsection