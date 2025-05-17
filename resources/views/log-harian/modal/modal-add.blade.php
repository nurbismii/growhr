@foreach($pekerjaan as $pk)
<div class="modal fade" id="sub-{{$pk->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Sub Pekerjaan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('log-harian.store.sub') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggalPelaporan" class="form-label">Tanggal
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control tanggalPelaporan" name="tanggal_pelaporan" id="tanggalPelaporan" readonly>
                            <input type="text" class="form-control" name="pekerjaan_id" value="{{ $pk->id }}" hidden>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sifatPekerjaan" class="form-label">Sifat Pekerjaan
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="sifat_pekerjaan" id="sifatPekerjaan" value="{{ $pk->getSifatPekerjaan->pekerjaan }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kategoriKegiatan" class="form-label">Kategori Pekerjaan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="kategoriKegiatan" name="kategori_pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Kategori Kegiatan --</option>
                                @foreach($kategori_pekerjaan as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->kategori_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pj_pekerjaan" class="form-label">Penanggung Jawab
                                <span class="text-danger">*</span>
                            </label>
                            <select id="pj_pekerjaan" name="pj_pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Penanggung Jawab --</option>
                                @foreach($user_modal as $um)
                                <option value="{{ $um->id }}">{{ $um->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="prioritas" class="form-label">Prioritas
                                <span class="text-danger">*</span>
                            </label>
                            <select id="prioritas" name="prioritas_id" class="form-select" required>
                                <option selected disabled>-- Pilih Prioritas --</option>
                                @foreach($prioritas as $priorit)
                                <option value="{{ $priorit->id }}">{{ $priorit->prioritas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="statusPekerjaan" class="form-label">Status Pekerjaan
                                <span class="text-danger">*</span>
                            </label>
                            <select id="statusPekerjaan" name="status_pekerjaan_id" class="form-select" required>
                                <option selected disabled>-- Pilih Status Pekerjaan --</option>
                                @foreach($status_pekerjaan as $sk)
                                <option value="{{ $sk->id }}">{{ $sk->status_pekerjaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsiTugas" class="form-label">Deskripsi Tugas
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" name="deskripsi_pekerjaan" id="deskripsiTugas" placeholder="Isi Tugas" rows="3" required readonly>{{ $pk->deskripsi_pekerjaan }}</textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="subDeskripsiPekerjaan" class="form-label">Sub Deskripsi Tugas
                                <sup>(Opsional)</sup>
                            </label>
                            <textarea class="form-control" name="sub_deskripsi_pekerjaan" id="subDeskripsiPekerjaan" placeholder="Isi sub Tugas" rows="3"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggalMulai" class="form-label">Tanggal Mulai
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control tanggalMulai" name="tanggal_mulai" id="tanggalMulai" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggalSelesai" class="form-label">Tanggal Selesai
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control tanggalSelesai" name="tanggal_selesai" id="tanggalSelesai" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label">Durasi
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control duration" name="durasi" id="duration" required readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="deadline" class="form-label">Deadline
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control deadline" name="deadline" id="deadline" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-secondary">
                                Tingkat Kesulitan <span class="text-danger">*</span>
                            </label>
                            <div class="slider-container">
                                <span>1</span>
                                <div class="slider-wrapper">
                                    <span class="slider-value">8</span>
                                    <input type="range" name="tingkat_kesulitan" class="slider-range" min="1" max="10" step="1" required>
                                </div>
                                <span>10</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="alasanPemilihan" class="form-label">Alasan pemilihan tingkat kesulitan
                                <span class="text-danger">*</span>
                            </label>
                            <textarea type="text" rows="2" class="form-control" name="alasan" id="alasanPemilihan" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Lampiran Dokumen <sup>(Opsional)</sup></label>
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