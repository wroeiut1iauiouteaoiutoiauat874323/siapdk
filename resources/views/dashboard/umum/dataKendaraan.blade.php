<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Kendaraan</h4>
        <form action="{{ route('dashboard.search', ['menu' => 'kendaraan']) }}" method="GET" class="d-flex me-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari kendaraan..." value="">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <a href="" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
        </a>
    </div>

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
                            <label for="nomorPolisi" class="form-label">Nomor Polisi</label>
                            <input type="text" class="form-control" id="nomorPolisi" name="nomorPolisi" required>
                        </div>
                        <div class="mb-3">
                            <label for="statusKendaraan" class="form-label">Status</label>
                            <input type="text" class="form-control" id="statusKendaraan" name="statusKendaraan" required>
                        </div>
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
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Kendaraan</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col">Nomor Polisi</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $kendaraan)
                                <tr>
                                    <td>{{ $kendaraan->id }}</td>
                                    <td>{{ $kendaraan->namaKendaraan }}</td>
                                    <td>{{ $kendaraan->jenisKendaraan }}</td>
                                    <td>{{ $kendaraan->nomorPolisi }}</td>
                                    <td>{{ $kendaraan->statusKendaraan }}</td>
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
                                                            <label for="nomorPolisi{{ $kendaraan->id }}" class="form-label">Nomor Polisi</label>
                                                            <input type="text" class="form-control" id="nomorPolisi{{ $kendaraan->id }}" name="nomorPolisi" value="{{ $kendaraan->nomorPolisi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="statusKendaraan{{ $kendaraan->id }}" class="form-label">Status</label>
                                                            <input type="text" class="form-control" id="statusKendaraan{{ $kendaraan->id }}" name="statusKendaraan" value="{{ $kendaraan->statusKendaraan }}" required>
                                                        </div>
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
                                    <td colspan="6" class="text-center">
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
