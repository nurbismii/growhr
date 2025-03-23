@foreach($pekerjaan as $pk)
<div class="modal fade" id="main-edit{{$pk->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pekerjaan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('log-harian.update', $pk->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('patch') }}
                    <button disabled class="btn btn-primary btn-lg mb-4">Form Pekerjaan</button>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalPelaporan" value="{{ date_format($pk->created_at, 'd/m/Y') }}" readonly>
                            <input type="text" class="form-control" name="pekerjaan_id" value="{{ $pk->id }}" hidden>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sifatPekerjaan" class="form-label">Sifat Pekerjaan</label>
                            <input type="text" class="form-control" value="{{ $pk->getSifatPekerjaan->pekerjaan }}" readonly>
                            <input type="hidden" class="form-control" name="sifat_pekerjaan" id="sifatPekerjaan" value="{{ $pk->getSifatPekerjaan->id }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kategoriKegiatan" class="form-label">Kategori Pekerjaan</label>
                            <select id="kategoriKegiatan" name="kategori_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pk->getKategoriPekerjaan->id }}">{{ $pk->getKategoriPekerjaan->kategori_pekerjaan }}</option>
                                @foreach($kategori_pekerjaan as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->kategori_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pj_pekerjaan" class="form-label">Penanggung Jawab</label>
                            <select id="pj_pekerjaan" name="pj_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pk->id }}">{{ $pk->getPjPekerjaan->name }}</option>
                                @foreach($user_modal as $um)
                                <option value="{{ $um->id }}">{{ $um->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option selected value="{{ $pk->getPrioritas->id }}">{{ $pk->getPrioritas->prioritas }}</option>
                                @foreach($prioritas as $priorit)
                                <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusPekerjaan" class="form-label">Status Pekerjaan</label>
                            <select id="statusPekerjaan" name="status_pekerjaan_id" class="form-select" required>
                                <option selected value="{{ $pk->getStatusPekerjaan->id }}">{{ $pk->getStatusPekerjaan->status_pekerjaan }}</option>
                                @foreach($status_pekerjaan as $sk)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control" name="deskripsi_pekerjaan" id="deskripsiTugas" placeholder="Isi Tugas" rows="3" required readonly>{{ $pk->deskripsi_pekerjaan }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" value="{{ $pk->tanggal_mulai }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" value="{{ $pk->tanggal_selesai }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Durasi</label>
                            <input type="text" class="form-control duration" name="durasi" id="duration" value="{{ $pk->durasi }}" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="text" class="form-control deadline" name="deadline" id="deadline" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-secondary">
                                Tingkat Kesulitan <span class="text-danger">*</span>
                            </label>
                            <div class="slider-container">
                                <span>1</span>
                                <div class="slider-wrapper">
                                    <span class="slider-value">{{ $pk->tingkat_kesulitan }}</span>
                                    <input type="range" name="tingkat_kesulitan" class="slider-range" min="1" max="10" step="1" value="{{ $pk->tingkat_kesulitan }}" required>
                                </div>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="alasanPemilihan" class="form-label">Alasan pemilihan tingkat kesulitan</label>
                            <input type="text" class="form-control" name="alasan" id="alasanPemilihan" value="{{ $pk->alasan }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Lampiran Dokumen (Opsional)</label>
                                <div>
                                    <label for="fileInput{{$pk->id}}" class="custom-file-upload">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="ms-2 fileLabel" id="fileLabel{{$pk->id}}">Pilih file</span>
                                    </label>
                                    <input type="file" id="fileInput{{$pk->id}}" class="fileInput" name="lampiran">
                                </div>
                                <div class="file-name fileName{{$pk->id}}"></div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white float-end">
                        Kirim &nbsp;<span class="tf-icons bx bx-send"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach