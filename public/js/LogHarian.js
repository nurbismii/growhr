
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];

        // Pilih semua elemen dengan class 'tanggalPelaporan' (gunakan class untuk multiple elements)
        document.querySelectorAll(".tanggalPelaporan").forEach(function(input) {
            input.value = today;
        });
    });

    $(document).ready(function() {
        function getCurrentMonthRange() {
            let start = moment().startOf('month'); // Hari pertama bulan ini
            let end = moment().endOf('month'); // Hari terakhir bulan ini

            return {
                start,
                end
            };
        }

        let {
            start,
            end
        } = getCurrentMonthRange();

        $('.daterange').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
    });



    $(document).ready(function() {
        $('.select-pekerjaan').select2({
            theme: 'bootstrap-5',
            placeholder: "Pekerjaan",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    $(document).ready(function() {
        $('.select-prioritas').select2({
            theme: 'bootstrap-5',
            placeholder: "Prioritas",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });

    $(document).ready(function() {
        $('.select-pic').select2({
            theme: 'bootstrap-5',
            placeholder: "PIC",
            allowClear: true // Memungkinkan pengguna menghapus pilihan
        });
    });



    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);

        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0'); // Januari = 0
        const yyyy = date.getFullYear();
        const hh = String(date.getHours()).padStart(2, '0');
        const mi = String(date.getMinutes()).padStart(2, '0');
        const ss = String(date.getSeconds()).padStart(2, '0');

        return `${dd}-${mm}-${yyyy}`;
    }

    function escapeHtml(text) {
        if (typeof text !== "string") {
            return text ?? ""; // Jika null atau undefined, kembalikan string kosong
        }
        return text.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    $.ajax({
        url: "/log-harian",
        type: "GET",
        success: function(response) {
            let statusOptions = "";

            response.status_pekerjaan.forEach(function(status) {
                statusOptions += `<option value="${status.id}">${status.status_pekerjaan}</option>`;
            });

            // Simpan ke variabel global agar bisa dipakai di sub-table
            window.statusOptions = statusOptions;
        }
    });

    $(document).ready(function() {
        let table = $('#log-harian').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true
        });

        function fetchData() {
            let formData = $('#search-form').serialize();

            $.ajax({
                url: "{{ route('log-harian.index') }}",
                type: "GET",
                data: formData,
                success: function(response) {
                    table.clear().draw();

                    response.pekerjaan.forEach(function(kerjaan, index) {

                        let statusOptions = response.status_pekerjaan.map(sp =>
                            `<option value="${sp.id}" ${kerjaan.get_status_pekerjaan.id === sp.id ? "selected" : ""}>${sp.status_pekerjaan}</option>`
                        ).join("");

                        table.row.add([
                            index + 1,
                            '<button class="btn btn-sm btn-primary toggle-btn" data-id="' + kerjaan.id + '">+</button>',
                            kerjaan.get_user.name,
                            formatTimestamp(kerjaan.created_at),
                            kerjaan.get_sifat_pekerjaan.pekerjaan,
                            kerjaan.deskripsi_pekerjaan,
                            kerjaan.get_prioritas.prioritas,
                            `<select class="form-select main-status">
                                ${statusOptions}
                            </select>`,
                            kerjaan.get_kategori_pekerjaan.kategori_pekerjaan,
                            kerjaan.tanggal_mulai,
                            kerjaan.tanggal_selesai,
                            kerjaan.durasi,
                            kerjaan.deadline,
                            kerjaan.get_pj_pekerjaan.name,
                            kerjaan.tingkat_kesulitan + "/10",
                            kerjaan.alasan,
                            kerjaan.lampiran ?? '-',
                            kerjaan.feedback_atasan ?? '-',
                            '<div class="dropdown">' +
                            '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                            '<i class="bx bx-edit"></i>' +
                            '</button>' +
                            '<div class="dropdown-menu">' +
                            '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-' + kerjaan.id + '">' +
                            '<i class="bx bx-plus-circle me-2"></i> Tambah' +
                            '</a>' +
                            '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-edit' + kerjaan.id + '">' +
                            '<i class="bx bx-edit-alt me-2"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item delete-btn" href="' + "{{ route('log-harian.destroy', ':id') }}".replace(':id', kerjaan.id) + '" data-confirm-delete="true">' +
                            '<i class="bx bx-trash me-2"></i> Delete' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        ]).draw();
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Update status pekerjaan otomatis saat user mengganti select
        $('#log-harian tbody').on('change', '.main-status', function() {
            let newStatusId = $(this).val(); // Ambil ID status yang dipilih
            let kerjaanId = $(this).closest('tr').find('.toggle-btn').data('id'); // Ambil ID pekerjaan dari tombol toggle

            if (!kerjaanId) {
                Swal.fire({
                    title: "Error!",
                    text: "ID pekerjaan tidak ditemukan!",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            // Kirim update ke backend
            $.ajax({
                url: "/log-harian/update-status-pekerjaan/" + kerjaanId,
                type: "POST",
                data: {
                    status_pekerjaan_id: newStatusId
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Status pekerjaan telah diperbarui.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat memperbarui status.",
                        icon: "error",
                        confirmButtonText: "Coba Lagi"
                    });
                    console.error("Gagal memperbarui status pekerjaan:", xhr.responseText);
                }
            });
        });

        // Trigger pencarian otomatis saat filter berubah
        $('#search-form select, #search-form input').on('change', function() {
            fetchData();
        });

        // Fungsi untuk menampilkan sub pekerjaan
        $('#log-harian tbody').on('click', '.toggle-btn', function() {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let id = $(this).data('id');

            if (row.child.isShown()) {
                row.child.hide();
                $(this).text('+').removeClass('btn-secondary').addClass('btn-primary');
            } else {
                $.ajax({
                    url: "/log-harian/sub/" + id,
                    type: "GET",
                    success: function(subPekerjaan) {
                        let subTableHtml = '<table class="table table-hover"><thead><tr>' +
                            '<th>No</th><th></th><th>PIC</th><th>Tanggal</th><th>Sifat Pekerjaan</th>' +
                            '<th>Deskripsi Pekerjaan</th><th>Prioritas</th><th>Status Pekerjaan</th>' +
                            '<th>Kategori Pekerjaan</th><th>Mulai</th><th>Selesai</th><th>Durasi</th>' +
                            '<th>Deadline</th><th>Penanggung Jawab</th><th>Tingkat Kesulitan</th>' +
                            '<th>Alasan</th><th>Lampiran</th><th>Feedback Atasan</th><th>Aksi</th></tr></thead><tbody>';

                        subPekerjaan.forEach(function(sub, index) {
                            subTableHtml += '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td></td>' +
                                '<td>' + escapeHtml(sub.get_user.name) + '</td>' +
                                '<td>' + formatTimestamp(sub.created_at) + '</td>' +
                                '<td>' + escapeHtml(sub.get_sifat_pekerjaan.pekerjaan) + '</td>' +
                                '<td>' + escapeHtml(sub.deskripsi_pekerjaan) + '</td>' +
                                '<td>' + escapeHtml(sub.get_prioritas.prioritas) + '</td>' +
                                '<td>' +
                                '<select class="form-select sub-status" data-id="' + sub.id + '">' +
                                '<option value="' + sub.get_status_pekerjaan.id + '" selected>' + escapeHtml(sub.get_status_pekerjaan.status_pekerjaan) + '</option>' +
                                statusOptions +
                                '</select>' +
                                '</td>' +
                                '<td>' + escapeHtml(sub.get_kategori_pekerjaan.kategori_pekerjaan) + '</td>' +
                                '<td>' + escapeHtml(sub.tanggal_mulai) + '</td>' +
                                '<td>' + escapeHtml(sub.tanggal_selesai) + '</td>' +
                                '<td>' + escapeHtml(sub.durasi) + '</td>' +
                                '<td>' + escapeHtml(sub.deadline) + '</td>' +
                                '<td>' + escapeHtml(sub.get_pj_pekerjaan.name) + '</td>' +
                                '<td>' + escapeHtml(sub.tingkat_kesulitan) + '/10</td>' +
                                '<td>' + escapeHtml(sub.alasan) + '</td>' +
                                '<td>' + (sub.lampiran ?? '-') + '</td>' +
                                '<td>' + (sub.feedback_atasan ?? '-') + '</td>' +
                                '<td>' +
                                '<div class="dropdown">' +
                                '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                '<i class="bx bx-edit"></i>' +
                                '</button>' +
                                '<div class="dropdown-menu">' +
                                '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#sub-edit' + sub.id + '">' +
                                '<i class="bx bx-edit-alt me-2"></i> Edit' +
                                '</a>' +
                                '<a class="dropdown-item delete-btn" href="' + "{{ route('log-harian.destroy.sub', ':id') }}".replace(':id', sub.id) + '" data-confirm-delete="true">' +
                                '<i class="bx bx-trash me-2"></i> Delete' +
                                '</a>' +
                                '</div>' +
                                '</div>' +
                                '</td>' +
                                '</tr>';
                        });

                        subTableHtml += '</tbody></table>';
                        row.child(subTableHtml).show();
                        $(this).text('-').removeClass('btn-primary').addClass('btn-secondary');
                    }
                });
            }
        });

        $('#log-harian tbody').on('change', '.sub-status', function() {
            let newStatusId = $(this).val();
            let subPekerjaanId = $(this).data('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                url: "/log-harian/update-status-pekerjaan/" + subPekerjaanId,
                type: "POST",
                data: {
                    status_pekerjaan_id: newStatusId
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Status Diperbarui!",
                        text: "Status pekerjaan sub berhasil diperbarui.",
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Gagal memperbarui status pekerjaan sub.",
                    });
                }
            });
        });
    });



    function calculateDays(event) {
        const modal = event.target.closest(".modal"); // Cari modal terdekat dari elemen yang berubah
        if (!modal) return;

        const startDateInput = modal.querySelector(".tanggalMulai");
        const endDateInput = modal.querySelector(".tanggalSelesai");
        const durationInput = modal.querySelector(".duration");

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (!isNaN(startDate) && !isNaN(endDate)) {
            if (endDate < startDate) {
                Swal.fire({
                    icon: "error",
                    title: "Tanggal Tidak Valid!",
                    text: "Tanggal Selesai tidak boleh lebih kecil dari Tanggal Mulai.",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                });

                endDateInput.value = ""; // Reset input
                durationInput.value = "";
                return;
            }

            const timeDiff = endDate - startDate;
            const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
            durationInput.value = daysDiff + " Hari";
        } else {
            durationInput.value = "";
        }
    }

    // Event delegation untuk menangani semua modal
    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("tanggalMulai") || event.target.classList.contains("tanggalSelesai")) {
            calculateDays(event);
        }
    });



    document.querySelectorAll(".slider-container").forEach(container => {
        const slider = container.querySelector(".slider-range");
        const sliderValue = container.querySelector(".slider-value");

        function updateValuePosition() {
            const percent = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
            sliderValue.style.left = `calc(${percent}% - 5px)`;
            sliderValue.textContent = slider.value;
        }

        slider.addEventListener("input", updateValuePosition);

        // Set nilai default
        slider.value = 8;
        updateValuePosition();
    });


    document.querySelectorAll('.form-select').forEach(select => {
        select.addEventListener('change', function() {
            let kerjaanId = this.closest('tr').dataset.id; // Pastikan tr memiliki data-id
            let statusId = this.value;

            fetch(`log-harian/update-status-pekerjaan/${kerjaanId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status_id: statusId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Status updated:', data.message);
                })
                .catch(error => console.error('Error:', error));
        });
    });
