<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Transaksi Barang</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'tbarang']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari transaksi..." value="" style="min-width:180px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode, nama barang, kategori, atau nama peminjam</small>
        </div>
        <a href="" class="btn btn-success shadow-sm px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        <label for="namaPegawai" class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" id="namaPegawai" name="nama_pegawai" required>
                    </div>
                    <div class="mb-3">
                        <label for="statusPegawai" class="form-label">Status Pegawai</label>
                        <select class="form-select" id="statusPegawai" name="status_pegawai" required>
                            <option value="" disabled selected>Pilih status pegawai</option>
                            <option value="PNS">PNS</option>
                            <option value="PPPK">PPPK</option>
                            <option value="CPNS">CPNS</option>
                            <option value="CPPPK">CPPPK</option>
                            <option value="Honorer">Honorer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="namaBarang" class="form-label">Nama Barang</label>
                        <select class="form-select" id="nama_barang" name="nama_barang" required>
                            <option value="" disabled selected>Pilih barang</option>
                            @foreach($data_barang->sortBy('namaBarang') as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->namaBarang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenisTransaksi" class="form-label">Jenis Transaksi</label>
                        <select class="form-select" id="jenisTransaksi" name="jenisTransaksi" required>
                            <option value="" disabled selected>Pilih jenis transaksi</option>
                            <option value="Masuk">Masuk</option>
                            <option value="Keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahPinjam" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlahPinjam" name="jumlahPinjam" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalTransaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tanggalTransaksi" name="tanggal_transaksi" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktuTransaksi" class="form-label">Waktu Transaksi</label>
                        <input type="time" class="form-control" id="waktuTransaksi" name="waktu_transaksi" required step="1">
                        <small class="text-muted">Format 24 jam (contoh: 14:30)</small>
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
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Kode</th>
                                    <th scope="col" class="text-center">Tanggal Transaksi</th>
                                    <th scope="col" class="text-center">Waktu</th>
                                    <th scope="col" class="text-center">Nama Barang</th>
                                    <th scope="col" class="text-center">Kategori</th>
                                    <th scope="col" class="text-center">Nama Peminjam</th>
                                    <th scope="col" class="text-center">Status Peminjam</th>
                                    <th scope="col" class="text-center">Jenis Transaksi</th>
                                    <th scope="col" class="text-center">Jumlah</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" style="width: 100px; padding-left:25px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($datanya->currentPage() - 1) * $datanya->perPage() }}</td>
                                    <td class="text-center">{{ $transaksi->kode }}</td>
                                    <td class="text-center">{{ $transaksi->tanggal_transaksi }}</td>
                                    <td class="text-center">{{ $transaksi->waktu }}</td>
                                    <td class="text-center">{{ $transaksi->barang->namaBarang ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->barang->jenisBarangPersediaan ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->nama_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->status_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->jenisTransaksi }}</td>
                                    <td class="text-center">{{ $transaksi->jumlahPinjam }}</td>
                                    <td class="text-center">{{ $transaksi->statusTransaksi }}</td>
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
                                                        <label for="namaPegawai{{ $transaksi->id }}" class="form-label">Nama Peminjam</label>
                                                        <input type="text" class="form-control" id="namaPegawai{{ $transaksi->id }}" name="nama_pegawai" value="{{ $transaksi->nama_pegawai }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="statusPegawai{{ $transaksi->id }}" class="form-label">Status Pegawai</label>
                                                        <select class="form-select" id="statusPegawai{{ $transaksi->id }}" name="status_pegawai" required>
                                                            <option value="" disabled {{ !$transaksi->status_pegawai ? 'selected' : '' }}>Pilih status pegawai</option>
                                                            <option value="PNS" {{ $transaksi->status_pegawai == 'PNS' ? 'selected' : '' }}>PNS</option>
                                                            <option value="PPPK" {{ $transaksi->status_pegawai == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                                            <option value="CPNS" {{ $transaksi->status_pegawai == 'CPNS' ? 'selected' : '' }}>CPNS</option>
                                                            <option value="CPPPK" {{ $transaksi->status_pegawai == 'CPPPK' ? 'selected' : '' }}>CPPPK</option>
                                                            <option value="Honorer" {{ $transaksi->status_pegawai == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="namaBarang{{ $transaksi->id }}" class="form-label">Nama Barang</label>
                                                        <select class="form-select" id="namaBarang{{ $transaksi->id }}" name="nama_barang" required>
                                                            <option value="{{ $transaksi->idDataBarang }}" selected>{{ $transaksi->barang->namaBarang ?? '-' }}</option>
                                                            @foreach($data_barang->sortBy('namaBarang') as $barang)
                                                                @if($barang->id != $transaksi->idDataBarang)
                                                                    <option value="{{ $barang->id }}">{{ $barang->namaBarang }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jenisTransaksi{{ $transaksi->id }}" class="form-label">Jenis Transaksi</label>
                                                        <select class="form-select" id="jenisTransaksi{{ $transaksi->id }}" name="jenisTransaksi" required>
                                                            <option value="" disabled {{ !$transaksi->jenisTransaksi ? 'selected' : '' }}>Pilih jenis transaksi</option>
                                                            <option value="Masuk" {{ $transaksi->jenisTransaksi == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                                            <option value="Keluar" {{ $transaksi->jenisTransaksi == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlahPinjam{{ $transaksi->id }}" class="form-label">Jumlah</label>
                                                        <input type="number" class="form-control" id="jumlahPinjam{{ $transaksi->id }}" name="jumlahPinjam" value="{{ $transaksi->jumlahPinjam }}" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tanggalTransaksi{{ $transaksi->id }}" class="form-label">Tanggal Transaksi</label>
                                                        <input type="date" class="form-control" id="tanggalTransaksi{{ $transaksi->id }}" name="tanggal_transaksi" value="{{ $transaksi->tanggal_transaksi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="waktuTransaksi{{ $transaksi->id }}" class="form-label">Waktu Transaksi</label>
                                                        <input type="time" class="form-control" id="waktuTransaksi{{ $transaksi->id }}" name="waktu_transaksi" value="{{ $transaksi->waktu }}" required step="1">
                                                        <small class="text-muted">Format 24 jam (contoh: 14:30)</small>
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
                                    <td colspan="10" class="text-center">
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

