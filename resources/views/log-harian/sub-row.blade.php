<!-- SKIP DARI TABEL SEHINGGA TIDAK TERJADI ERROR -->
<tr class="sub-row sub-row-{{$kerjaan->id}}" style="display: none;">
    <td colspan="19">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>PIC</th>
                    <th>Tanggal</th>
                    <th>Sifat Pekerjaan</th>
                    <th>Deskripsi Pekerjaan</th>
                    <th>Prioritas</th>
                    <th>Status Pekerjaan</th>
                    <th>Kategori Pekerjaan</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Durasi</th>
                    <th>Deadline</th>
                    <th>Penanggung Jawab</th>
                    <th>Tingkat Kesulitan</th>
                    <th>Alasan pemilihan Tingkat </th>
                    <th>Lampiran</th>
                    <th>Feedback Atasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kerjaan->getSubPekerjaan as $subPekerjaan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $kerjaan->getUser->name }}</td>
                    <td>{{ date_format($subPekerjaan->created_at, 'd-m-Y') }}</td>
                    <td>{{ $kerjaan->getSifatPekerjaan->pekerjaan }}</td>
                    <td>{{ $kerjaan->deskripsi_pekerjaan }}</td>
                    <td>{{ $kerjaan->getPrioritas->prioritas }}</td>
                    <td>
                        <select class="form-select">
                            <option value="">{{ $subPekerjaan->getStatusPekerjaan->status_pekerjaan }}</option>
                            @foreach($status_pekerjaan as $sp)
                            @if($subPekerjaan->getStatusPekerjaan->id != $sp->id)
                            <option value="">{{ $sp->status_pekerjaan }}</option>
                            @endif
                            @endforeach
                        </select>
                    </td>
                    <td>{{ $subPekerjaan->getKategoriPekerjaan->kategori_pekerjaan }}</td>
                    <td>{{ $subPekerjaan->tanggal_mulai }}</td>
                    <td>{{ $subPekerjaan->tanggal_selesai }}</td>
                    <td>{{ $subPekerjaan->durasi }}</td>
                    <td>{{ $subPekerjaan->deadline }}</td>
                    <td>{{ $subPekerjaan->getPjPekerjaan->name }}</td>
                    <td>{{ $subPekerjaan->tingkat_kesulitan }}/10</td>
                    <td>{{ $subPekerjaan->alasan }}</td>
                    <td>{{ $subPekerjaan->lampiran ?? '-' }}</td>
                    <td>{{ $subPekerjaan->feedback_atasan ?? '-' }}</td>
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
                @endforeach
            </tbody>
        </table>
    </td>
</tr>
<!-- SKIP DARI TABEL SEHINGGA TIDAK TERJADI ERROR END -->