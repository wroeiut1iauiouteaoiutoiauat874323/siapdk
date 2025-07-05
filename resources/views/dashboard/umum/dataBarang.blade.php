
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Barang</h4>
        <form action="{{ route('dashboard.search', ['menu' => 'barang']) }}" method="GET" class="d-flex me-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..." value="">
            {{-- <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..." value="{{ request('search') }}"> --}}
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <a href="" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i> Tambah Barang
        </a>
    </div>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('proses.data_barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="namaBarang" name="namaBarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenisBarangPersediaan" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="jenisBarangPersediaan" name="jenisBarangPersediaan" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahTotal" class="form-label">Jumlah Total</label>
                            <input type="number" class="form-control" id="jumlahTotal" name="jumlahTotal" min="1" required>
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
        // Buka modal saat tombol Tambah Barang diklik
        document.querySelector('.btn-success').addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('modalTambahBarang'));
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
                                    <th scope="col" class="text-center">Nama Barang</th>
                                    <th scope="col" class="text-center">Kategori</th>
                                    <th scope="col" class="text-center">Jumlah Total</th>
                                    <th scope="col" class="text-center">Jumlah Tersedia</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $barang)
                                <tr>
                                    <td class="text-center">{{ $barang->id }}</td>
                                    <td class="text-center">{{ $barang->namaBarang }}</td>
                                    <td class="text-center">{{ $barang->jenisBarangPersediaan }}</td>
                                    <td class="text-center">{{ $barang->jumlahTotal }}</td>
                                    <td class="text-center">{{ $barang->jumlahTersedia }}</td>
                                    <td class="text-center">
                                    <!-- Tombol Edit -->
                                    <a href="#" class="btn btn-warning btn-sm btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditBarang{{ $barang->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Modal Edit Barang -->
                                    <div class="modal fade" id="modalEditBarang{{ $barang->id }}" tabindex="-1" aria-labelledby="modalEditBarangLabel{{ $barang->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('proses.data_barang.edit', $barang->id) }}" method="POST">
                                                {{-- <form action="" method="POST"> --}}
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditBarangLabel{{ $barang->id }}">Edit Barang</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="namaBarang{{ $barang->id }}" class="form-label">Nama Barang</label>
                                                            <input type="text" class="form-control" id="namaBarang{{ $barang->id }}" name="namaBarang" value="{{ $barang->namaBarang }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenisBarangPersediaan{{ $barang->id }}" class="form-label">Kategori</label>
                                                            <input type="text" class="form-control" id="jenisBarangPersediaan{{ $barang->id }}" name="jenisBarangPersediaan" value="{{ $barang->jenisBarangPersediaan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jumlahTotal{{ $barang->id }}" class="form-label">Jumlah Total</label>
                                                            <input type="number" class="form-control" id="jumlahTotal{{ $barang->id }}" name="jumlahTotal" value="{{ $barang->jumlahTotal }}" min="1" required>
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
                                        <form action="{{ route('proses.data_barang.hapus', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
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
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data barang.</td>
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
