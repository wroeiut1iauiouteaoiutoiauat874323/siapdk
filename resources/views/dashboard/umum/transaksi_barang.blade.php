<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Transaksi Barang</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'tbarang']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari transaksi..." value="" style="min-width:180px;" autofocus>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode transaksi, nama barang, kategori, kode barang, atau nama</small>
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
                        @if (isset($_COOKIE['status']) && $_COOKIE['status'] == 'umum')
                            <div class="mb-3">
                                <label for="kodeBarang" class="form-label">Kode Barang</label>
                                <input list="listKodeBarang" class="form-control" id="kodeBarang" name="kode_barang" placeholder="Ketik atau pilih kode barang...">
                                <datalist id="listKodeBarang">
                                    @foreach($data_barang->sortBy('kode') as $barang)
                                        <option value="{{ $barang->kode }}">{{ $barang->kode }}</option>
                                    @endforeach
                                </datalist>
                                <small class="text-muted">Ketik kode barang atau pilih dari daftar.</small>
                            </div>
                            <div class="mb-3">
                                <label for="namaBarang" class="form-label">Nama Barang</label>
                                <input list="listBarang" class="form-control" id="namaBarang" name="nama_barang" placeholder="Ketik atau pilih barang..." required>
                                <datalist id="listBarang">
                                    @foreach($data_barang->sortBy('namaBarang') as $barang)
                                        <option value="{{ $barang->namaBarang }}">{{ $barang->namaBarang }}</option>
                                    @endforeach
                                </datalist>
                                <small class="text-muted">Ketik nama barang atau pilih dari daftar.</small>
                            </div>
                            <div class="mb-3">
                                <label for="jenisBarangPersediaan" class="form-label">Kategori</label>
                                <input list="listKategori" class="form-control" id="jenisBarangPersediaan" name="jenisBarangPersediaan" placeholder="Ketik atau pilih kategori..." required>
                                <datalist id="listKategori">
                                    @foreach($data_barang->unique('jenisBarangPersediaan')->sortBy('jenisBarangPersediaan') as $barang)
                                        @if($barang->jenisBarangPersediaan)
                                            <option value="{{ $barang->jenisBarangPersediaan }}">{{ $barang->jenisBarangPersediaan }}</option>
                                        @endif
                                    @endforeach
                                </datalist>
                                <small class="text-muted">Ketik kategori atau pilih dari daftar.</small>
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="barangGabungan" class="form-label">Pilih Barang</label>
                                <select class="form-select" id="barangGabungan" name="gabungan_barang" required>
                                    <option value="" disabled selected>Pilih barang...</option>
                                    @foreach($data_barang->sortBy('namaBarang') as $barang)
                                        <option value="{{ $barang->kode }}">
                                            {{ $barang->kode }} - {{ $barang->namaBarang }} ({{ $barang->jenisBarangPersediaan }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih barang berdasarkan kode, nama, dan kategori.</small>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="lokasiBarang" class="form-label">Lokasi</label>
                            <input list="listLokasi" class="form-control" id="lokasiBarang" name="lokasiBarang" placeholder="Ketik atau pilih lokasi akan dituju" required>
                            <datalist id="listLokasi">
                                @foreach($data_barang->unique('lokasi')->sortBy('lokasi') as $barang)
                                    @if($barang->lokasi)
                                        <option value="{{ $barang->lokasi }}">{{ $barang->lokasi }}</option>
                                    @endif
                                @endforeach
                            </datalist>
                            <small class="text-muted">Ketik lokasi atau pilih dari daftar.</small>
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
                            <label for="tanggalTransaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="tanggalTransaksi" name="tanggal_transaksi" required>
                        </div>
                        <div class="mb-3">
                            <label for="waktuTransaksi" class="form-label">Waktu Transaksi</label>
                            <input type="time" class="form-control" id="waktuTransaksi" name="waktu_transaksi" required step="1">
                            <small class="text-muted">Format 24 jam (contoh: 14:30)</small>
                        </div>
                        <div class="mb-3">
                            <label for="alasanTransaksi" class="form-label">Keterangan Transaksi</label>
                            <textarea class="form-control" id="alasanTransaksi" name="alasan" rows="2" placeholder="Masukkan Keterangan Transaksi" required></textarea>
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
                                    <th scope="col" class="text-center">Kode Transaksi</th>
                                    <th scope="col" class="text-center">Tanggal Transaksi</th>
                                    <th scope="col" class="text-center">Waktu</th>
                                    <th scope="col" class="text-center">Kode Barang</th>
                                    <th scope="col" class="text-center">Nama Barang</th>
                                    <th scope="col" class="text-center">Kategori</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Jenis Transaksi</th>
                                    @if (isset($_COOKIE['status']) && $_COOKIE['status'] == 'umum')

                                        <th scope="col" style="width: 100px; padding-left:28px">Aksi</th>
                                    @else
                                        <th scope="col" style="width: 30px;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($datanya->currentPage() - 1) * $datanya->perPage() }}</td>
                                    <td class="text-center">{{ $transaksi->kode }}</td>
                                    <td class="text-center">{{ $transaksi->tanggal_transaksi }}</td>
                                    <td class="text-center">{{ $transaksi->waktu }}</td>
                                    <td class="text-center">{{ $transaksi->barang->kode ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->barang->namaBarang ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->barang->jenisBarangPersediaan ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->nama_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->status_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->jenisTransaksi }}</td>
                                    <td>
                                    <!-- Tombol Lihat Alasan -->
                                    <button type="button" class="btn btn-info btn-sm btn-action" title="Lihat Alasan" data-bs-toggle="modal" data-bs-target="#modalAlasanTransaksi{{ $transaksi->id }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>

                                    <!-- Modal Alasan Transaksi -->
                                    <div class="modal fade" id="modalAlasanTransaksi{{ $transaksi->id }}" tabindex="-1" aria-labelledby="modalAlasanTransaksiLabel{{ $transaksi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalAlasanTransaksiLabel{{ $transaksi->id }}">Keterangan Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <strong>Keterangan:</strong><br>
                                                    {{ $transaksi->alasan }}
                                                    <hr>
                                                    <strong>Lokasi:</strong><br>
                                                    {{ $transaksi->lokasi ?? '-' }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (isset($_COOKIE['status']) && $_COOKIE['status'] == 'umum')
                                        <form action="{{ route('proses.transaksi_barang.hapus', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-action" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif



                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="12" class="text-center">
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

