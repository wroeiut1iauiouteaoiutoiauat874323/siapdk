
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Transaksi Barang</h4>
        <form action="{{ route('dashboard.search', ['menu' => 'transaksi_barang']) }}" method="GET" class="d-flex me-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari transaksi..." value="">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <a href="" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
        </a>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('proses.transaksi_barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahTransaksiLabel">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="namaBarang" name="namaBarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenisTransaksi" class="form-label">Jenis Transaksi</label>
                            <input type="text" class="form-control" id="jenisTransaksi" name="jenisTransaksi" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalTransaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="tanggalTransaksi" name="tanggalTransaksi" required>
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
        // Buka modal saat tombol Tambah Transaksi diklik
        document.querySelector('.btn-success').addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('modalTambahTransaksi'));
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
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Jenis Transaksi</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Tanggal Transaksi</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->id }}</td>
                                    <td>{{ $transaksi->namaBarang }}</td>
                                    <td>{{ $transaksi->jenisTransaksi }}</td>
                                    <td>{{ $transaksi->jumlah }}</td>
                                    <td>{{ $transaksi->tanggalTransaksi }}</td>
                                    <td>
                                    <!-- Tombol Edit -->
                                    <a href="#" class="btn btn-warning btn-sm btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditTransaksi{{ $transaksi->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Modal Edit Transaksi -->
                                    <div class="modal fade" id="modalEditTransaksi{{ $transaksi->id }}" tabindex="-1" aria-labelledby="modalEditTransaksiLabel{{ $transaksi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('proses.transaksi_barang.edit', $transaksi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditTransaksiLabel{{ $transaksi->id }}">Edit Transaksi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="namaBarang{{ $transaksi->id }}" class="form-label">Nama Barang</label>
                                                            <input type="text" class="form-control" id="namaBarang{{ $transaksi->id }}" name="namaBarang" value="{{ $transaksi->namaBarang }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenisTransaksi{{ $transaksi->id }}" class="form-label">Jenis Transaksi</label>
                                                            <input type="text" class="form-control" id="jenisTransaksi{{ $transaksi->id }}" name="jenisTransaksi" value="{{ $transaksi->jenisTransaksi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlah{{ $transaksi->id }}" class="form-label">Jumlah</label>
                                                            <input type="number" class="form-control" id="jumlah{{ $transaksi->id }}" name="jumlah" value="{{ $transaksi->jumlah }}" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggalTransaksi{{ $transaksi->id }}" class="form-label">Tanggal Transaksi</label>
                                                            <input type="date" class="form-control" id="tanggalTransaksi{{ $transaksi->id }}" name="tanggalTransaksi" value="{{ $transaksi->tanggalTransaksi }}" required>
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
                                        <form action="{{ route('proses.transaksi_barang.hapus', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data transaksi.</td>
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

