<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Kendaraan</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'kendaraan']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari kendaraan..." value="{{ request('search') }}" style="min-width:180px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode, nama, jenis, nomor polisi, atau ciri kendaraan</small>
        </div>
        <a href="" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
        </a>
    </div>

    @if($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Modal Tambah Kendaraan -->
    <div class="modal fade" id="modalTambahKendaraan" tabindex="-1" aria-labelledby="modalTambahKendaraanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('proses.data_kendaraan.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKendaraanLabel">Tambah Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaKendaraan" class="form-label">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="namaKendaraan" name="namaKendaraan" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenisKendaraan" class="form-label">Jenis</label>
                            <input type="text" class="form-control" id="jenisKendaraan" name="jenisKendaraan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomorPolisi" class="form-label">Nomor Polisi atau Ciri Kendaraan</label>
                            <input type="text" class="form-control" id="nomorPolisi" name="nomorPolisi" required>
                            <small class="form-text text-muted">Jika tidak ada nomor polisi, isi dengan ciri-ciri kendaraan.</small>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <select class="form-select" id="keterangan" name="keterangan" required onchange="toggleAlasanKendaraan(this)">
                                <option value="" disabled selected>Pilih keterangan kendaraan</option>
                                <option value="Ada">Ada</option>
                                <option value="Tidak Ada">Tidak Ada</option>
                            </select>
                            <small class="form-text text-muted">Pilih <b>Ada</b> jika kendaraan masih ada. Jika <b>Tidak Ada</b>, harap isi alasan di bawah.</small>
                        </div>
                        <div class="mb-3 d-none" id="alasanKendaraanWrapper">
                            <label for="alasanKendaraan" class="form-label">Alasan Tidak Ada</label>
                            <input type="text" class="form-control" id="alasanKendaraan" name="alasanKendaraan" placeholder="Contoh: Sudah dijual, hilang, rusak berat, dll.">
                        </div>
                        <script>
                            function toggleAlasanKendaraan(select) {
                                var alasanWrapper = document.getElementById('alasanKendaraanWrapper');
                                var alasanInput = document.getElementById('alasanKendaraan');
                                if (select.value === 'Tidak Ada') {
                                    alasanWrapper.classList.remove('d-none');
                                    alasanInput.required = true;
                                } else {
                                    alasanWrapper.classList.add('d-none');
                                    alasanInput.required = false;
                                    alasanInput.value = '';
                                }
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <script>
        // Buka modal saat tombol Tambah Kendaraan diklik
        document.querySelector('.btn-success').addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('modalTambahKendaraan'));
            modal.show();
        });
    </script>
    <div class="row justify-content-center">
        <div class="col-md-12 ">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Kode</th>
                                    <th scope="col" class="text-center">Nama Kendaraan</th>
                                    <th scope="col" class="text-center">Jenis</th>
                                    <th scope="col" class="text-center">Nomor Polisi</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Keterangan</th>
                                    <th scope="col" style="width: 100px; padding-left:25px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $kendaraan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($datanya->currentPage() - 1) * $datanya->perPage() }}</td>
                                    <td class="text-center">{{ $kendaraan->kode }}</td>
                                    <td class="text-center">{{ $kendaraan->namaKendaraan }}</td>
                                    <td class="text-center">{{ $kendaraan->jenisKendaraan }}</td>
                                    <td class="text-center">{{ $kendaraan->nomorPolisi }}</td>
                                    <td class="text-center">{{ $kendaraan->status }}</td>
                                    <td class="text-center">{{ $kendaraan->keterangan }}</td>
                                    <td>
                                    <!-- Tombol Edit -->
                                    <a href="#" class="btn btn-warning btn-sm btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditKendaraan{{ $kendaraan->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Modal Edit Kendaraan -->
                                    <div class="modal fade" id="modalEditKendaraan{{ $kendaraan->id }}" tabindex="-1" aria-labelledby="modalEditKendaraanLabel{{ $kendaraan->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('proses.data_kendaraan.edit', $kendaraan->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditKendaraanLabel{{ $kendaraan->id }}">Edit Kendaraan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="namaKendaraan{{ $kendaraan->id }}" class="form-label">Nama Kendaraan</label>
                                                            <input type="text" class="form-control" id="namaKendaraan{{ $kendaraan->id }}" name="namaKendaraan" value="{{ $kendaraan->namaKendaraan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenisKendaraan{{ $kendaraan->id }}" class="form-label">Jenis</label>
                                                            <input type="text" class="form-control" id="jenisKendaraan{{ $kendaraan->id }}" name="jenisKendaraan" value="{{ $kendaraan->jenisKendaraan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nomorPolisi{{ $kendaraan->id }}" class="form-label">Nomor Polisi atau Ciri Kendaraan</label>
                                                            <input type="text" class="form-control" id="nomorPolisi{{ $kendaraan->id }}" name="nomorPolisi" value="{{ $kendaraan->nomorPolisi }}" required>
                                                            <small class="form-text text-muted">Jika tidak ada nomor polisi, isi dengan ciri-ciri kendaraan.</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="keterangan{{ $kendaraan->id }}" class="form-label">Keterangan</label>
                                                            <select class="form-select" id="keterangan{{ $kendaraan->id }}" name="keterangan" required onchange="toggleAlasanKendaraanEdit(this, '{{ $kendaraan->id }}')">
                                                                <option value="" disabled {{ $kendaraan->keterangan == null ? 'selected' : '' }}>Pilih keterangan kendaraan</option>
                                                                <option value="Ada" {{ $kendaraan->keterangan == 'Ada' ? 'selected' : '' }}>Ada</option>
                                                                <option value="Tidak Ada" {{ $kendaraan->keterangan == 'Tidak Ada' || Str::contains($kendaraan->keterangan, 'Tidak Ada') ? 'selected' : '' }}>Tidak Ada</option>
                                                            </select>
                                                            <small class="form-text text-muted">Pilih <b>Ada</b> jika kendaraan masih ada. Jika <b>Tidak Ada</b>, harap isi alasan di bawah.</small>
                                                        </div>
                                                        <div class="mb-3 {{ $kendaraan->keterangan == 'Tidak Ada' || Str::contains($kendaraan->keterangan, 'Tidak Ada') ? '' : 'd-none' }}" id="alasanKendaraanWrapperEdit{{ $kendaraan->id }}">
                                                            <label for="alasanKendaraanEdit{{ $kendaraan->id }}" class="form-label">Alasan Tidak Ada</label>
                                                            <input type="text" class="form-control" id="alasanKendaraanEdit{{ $kendaraan->id }}" name="alasanKendaraan" placeholder="Contoh: Sudah dijual, hilang, rusak berat, dll." value="{{ $kendaraan->alasanKendaraan ?? '' }}" {{ $kendaraan->keterangan == 'Tidak Ada' || Str::contains($kendaraan->keterangan, 'Tidak Ada') ? 'required' : '' }}>
                                                        </div>
                                                        <script>
                                                            function toggleAlasanKendaraanEdit(select, id) {
                                                                var alasanWrapper = document.getElementById('alasanKendaraanWrapperEdit' + id);
                                                                var alasanInput = document.getElementById('alasanKendaraanEdit' + id);
                                                                if (select.value === 'Tidak Ada') {
                                                                    alasanWrapper.classList.remove('d-none');
                                                                    alasanInput.required = true;
                                                                } else {
                                                                    alasanWrapper.classList.add('d-none');
                                                                    alasanInput.required = false;
                                                                    alasanInput.value = '';
                                                                }
                                                            }
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var select = document.getElementById('keterangan{{ $kendaraan->id }}');
                                                                if (select && select.value === 'Tidak Ada') {
                                                                    toggleAlasanKendaraanEdit(select, '{{ $kendaraan->id }}');
                                                                }
                                                            });
                                                        </script>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                        <form action="{{ route('proses.data_kendaraan.hapus', $kendaraan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-action" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data kendaraan.</td>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="9" class="text-center">
                                        {{ $datanya->links() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
