<div class="card">
    <div class="card-body">
        <div class="row">
            @foreach($pekerjaan as $job)
            <div class="col-md-4 mb-3 filter-item" data-divisi="{{ $job->getUser->divisi_id }}">
                <div class="card shadow-sm border-1 border-primary">
                    <div class="card-body">
                        <h6 class="bold text-uppercase text-primary">
                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="{{  $job->deskripsi_pekerjaan }}">{{ substr($job->deskripsi_pekerjaan, 0, 30) }} -</a>
                            @if($job->getUser->image)
                            <img src="{{ asset('img/profile/' . $job->getUser->image) }}" alt="PIC" class="rounded-circle" width="30">
                            @else
                            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="PIC" class="rounded-circle" width="30">
                            @endif
                        </h6>
                        <i><small class="text-black mt-0">{{ $job->getStatusPekerjaan->status_pekerjaan }}</small></i>

                        <ul class="list-unstyled mt-2">
                            @foreach($job->getSubPekerjaan as $sub)
                            <li class="border-bottom py-1 mt-1">
                                <small>{{ $sub->tanggal_mulai }}: {{ $sub->getKategoriPekerjaan->kategori_pekerjaan }}</small>
                            </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-end mt-2">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detail{{ $job->id }}">
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@foreach($pekerjaan as $kerjaan)
<div class="modal fade" id="detail{{ $kerjaan->id }}" tabindex="-1" aria-labelledby="detailLabel{{ $kerjaan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailLabel{{ $kerjaan->id }}">Detail Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">PIC</div>
                    <div class="col-8">{{ $kerjaan->getUser->name }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Tanggal Pencatatan</div>
                    <div class="col-8">{{ date_format($kerjaan->created_at, 'Y-m-d') }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Sifat Pekerjaan</div>
                    <div class="col-8">{{ $kerjaan->getSifatPekerjaan->pekerjaan }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Deskripsi</div>
                    <div class="col-8">{{ $kerjaan->deskripsi_pekerjaan }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Prioritas</div>
                    <div class="col-8">{{ $kerjaan->getPrioritas->prioritas }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Status</div>
                    <div class="col-8">{{ $kerjaan->getStatusPekerjaan->status_pekerjaan }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Kategori</div>
                    <div class="col-8">{{ $kerjaan->getKategoriPekerjaan->kategori_pekerjaan }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Tanggal Mulai</div>
                    <div class="col-8">{{ $kerjaan->tanggal_mulai }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Tanggal Selesai</div>
                    <div class="col-8">{{ $kerjaan->tanggal_selesai }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Durasi</div>
                    <div class="col-8">{{ $kerjaan->durasi }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Penanggung Jawab</div>
                    <div class="col-8">{{ $kerjaan->getPjPekerjaan->name }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Tingkat Kesulitan</div>
                    <div class="col-8">{{ $kerjaan->tingkat_kesulitan }}/10</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Alasan Kesulitan</div>
                    <div class="col-8">{{ $kerjaan->alasan }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Lampiran</div>
                    <div class="col-8">
                        @if($kerjaan->lampiran)
                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ asset('lampiran/pekerjaan/' . $kerjaan->lampiran) }}">
                            <i class="bx bx-link-alt me-1"></i> Lihat Lampiran
                        </a>
                        @else
                        <span class="text-muted">Tidak ada lampiran</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Feedback Atasan</div>
                    <div class="col-8 fst-italic">{{ $kerjaan->feedback_atasan ?? 'Belum ada feedback' }}</div>
                </div>
                <div class="row mb-2 border-bottom">
                    <div class="col-4 fw-semibold">Total Sub Pekerjaan</div>
                    <div class="col-8 fst-italic">{{ $kerjaan->getSubPekerjaan->count() }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach