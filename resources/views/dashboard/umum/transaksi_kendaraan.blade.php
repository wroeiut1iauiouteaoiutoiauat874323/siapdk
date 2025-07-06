<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Transaksi Kendaraan</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'tkendaraan']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari transaksi..." value="" style="min-width:180px;" autofocus>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode, nama, jenis, nomor polisi, atau ciri kendaraan atau nama</small>
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
                <form action="{{ route('proses.transaksi_kendaraan.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahTransaksiLabel">Tambah Transaksi Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="namaPegawai" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="namaPegawai" name="nama_pegawai" required placeholder="Masukkan nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="statusPegawai" class="form-label">Status</label>
                            <select class="form-select" id="statusPegawai" name="status_pegawai" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="CPNS">CPNS</option>
                                <option value="CPPPK">CPPPK</option>
                                <option value="Honorer">Honorer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Kendaraan</label>
                            <input list="listKode" class="form-control" id="kode" name="kode" placeholder="Ketik atau pilih kode..." value="{{ old('kode') }}">
                            <datalist id="listKode">
                                @foreach($data_kendaraan->unique('kode')->sortBy('kode') as $kendaraan)
                                    @if($kendaraan->kode)
                                        <option value="{{ $kendaraan->kode }}">{{ $kendaraan->kode }}</option>
                                    @endif
                                @endforeach
                            </datalist>
                            <small class="text-muted">Ketik kode atau pilih dari daftar. Jika menambah kendaraan baru, biarkan kosong.</small>
                        </div>
                        <div class="mb-3">
                            <label for="namaKendaraan" class="form-label">Nama Kendaraan</label>
                            <input list="listKendaraan" class="form-control" id="namaKendaraan" name="nama_kendaraan" placeholder="Ketik atau pilih kendaraan..." required>
                            <datalist id="listKendaraan">
                                @foreach($data_kendaraan->unique('namaKendaraan')->sortBy('namaKendaraan') as $kendaraan)
                                    <option value="{{ $kendaraan->namaKendaraan }}">{{ $kendaraan->namaKendaraan }}</option>
                                @endforeach
                            </datalist>
                            <small class="text-muted">Ketik nama kendaraan atau pilih dari daftar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="jenisKendaraan" class="form-label">Jenis Kendaraan</label>
                            <input list="listJenisKendaraan" class="form-control" id="jenisKendaraan" name="jenis_kendaraan" placeholder="Ketik atau pilih jenis kendaraan..." required>
                            <datalist id="listJenisKendaraan">
                                @foreach($data_kendaraan->unique('jenisKendaraan')->sortBy('jenisKendaraan') as $kendaraan)
                                    @if($kendaraan->jenisKendaraan)
                                        <option value="{{ $kendaraan->jenisKendaraan }}">{{ $kendaraan->jenisKendaraan }}</option>
                                    @endif
                                @endforeach
                            </datalist>
                            <small class="text-muted">Ketik jenis kendaraan atau pilih dari daftar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="nomorPolisi" class="form-label">Nomor Polisi atau Ciri Kendaraan</label>
                            <input list="listNopol" class="form-control" id="nomorPolisi" name="nomor_polisi" placeholder="Ketik atau pilih nomor polisi..." required>
                            <datalist id="listNopol">
                                @foreach($data_kendaraan->unique('nomorPolisi')->sortBy('nomorPolisi') as $kendaraan)
                                    @if($kendaraan->nomorPolisi)
                                        <option value="{{ $kendaraan->nomorPolisi }}">{{ $kendaraan->nomorPolisi }}</option>
                                    @endif
                                @endforeach
                            </datalist>
                            <small class="text-muted">Ketik nomor polisi atau pilih dari daftar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="lokasiKendaraan" class="form-label">Lokasi Kendaraan</label>
                            <input list="listLokasi" class="form-control" id="lokasiKendaraan" name="lokasi" placeholder="Ketik atau pilih lokasi kendaraan..." required>
                            <datalist id="listLokasi">
                                @foreach($data_kendaraan->unique('lokasi')->sortBy('lokasi') as $kendaraan)
                                    @if($kendaraan->lokasi)
                                        <option value="{{ $kendaraan->lokasi }}">{{ $kendaraan->lokasi }}</option>
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
                            <label for="alasan" class="form-label">Keterangan Transaksi</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Masukkan Keterangan Transaksi" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                <script>
                    function updateNomorPolisi() {
                        var select = document.getElementById('nama_kendaraan');
                        var nopol = select.options[select.selectedIndex]?.getAttribute('data-nopol') || '';
                        document.getElementById('nomorPolisi').value = nopol;
                    }
                </script>
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
                                    <th scope="col" class="text-center">Kode Kendaraan</th>
                                    <th scope="col" class="text-center">Nama Kendaraan</th>
                                    <th scope="col" class="text-center">Jenis Kendaraan</th>
                                    <th scope="col" class="text-center">No Polisi atau Ciri Kendaraan</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Jenis Transaksi</th>
                                    <th scope="col" style="width: 135px; padding-left:50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($datanya->currentPage() - 1) * $datanya->perPage() }}</td>
                                    <td class="text-center">{{ $transaksi->kode }}</td>
                                    <td class="text-center">{{ $transaksi->tanggal_transaksi }}</td>
                                    <td class="text-center">{{ $transaksi->waktu }}</td>
                                    <td class="text-center">{{ $transaksi->kendaraan->kode ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->kendaraan->namaKendaraan ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->kendaraan->jenisKendaraan ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->kendaraan->nomorPolisi ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksi->nama_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->status_pegawai }} </td>
                                    <td class="text-center">{{ $transaksi->jenisTransaksi }}</td>
                                    <td>
                                    <!-- Tombol Lihat Alasan -->
                                    <button type="button" class="btn btn-info btn-sm btn-action" title="Lihat Alasan" data-bs-toggle="modal" data-bs-target="#modalAlasanTransaksi{{ $transaksi->id }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <!-- Modal Keterangan Transaksi -->
                                    <div class="modal fade" id="modalAlasanTransaksi{{ $transaksi->id }}" tabindex="-1" aria-labelledby="modalAlasanTransaksiLabel{{ $transaksi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalAlasanTransaksiLabel{{ $transaksi->id }}">Keterangan Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Keterangan:</strong> {{ $transaksi->alasan ?? 'Tidak ada keterangan yang diberikan.' }}</p>
                                                    <p><strong>Lokasi:</strong> {{ $transaksi->lokasi ?? '-' }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tombol Edit -->
                                    <a href="#" class="btn btn-warning btn-sm btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditTransaksi{{ $transaksi->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Modal Edit Transaksi -->
                                    <div class="modal fade" id="modalEditTransaksi{{ $transaksi->id }}" tabindex="-1" aria-labelledby="modalEditTransaksiLabel{{ $transaksi->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('proses.transaksi_kendaraan.edit', $transaksi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditTransaksiLabel{{ $transaksi->id }}">Edit Transaksi Kendaraan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="namaPegawai{{ $transaksi->id }}" class="form-label">Nama</label>
                                                            <input type="text" class="form-control" id="namaPegawai{{ $transaksi->id }}" name="nama_pegawai" value="{{ $transaksi->nama_pegawai }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="statusPegawai{{ $transaksi->id }}" class="form-label">Status</label>
                                                            <select class="form-select" id="statusPegawai{{ $transaksi->id }}" name="status_pegawai" required>
                                                                <option value="" disabled {{ !$transaksi->status_pegawai ? 'selected' : '' }}>Pilih Status</option>
                                                                <option value="PNS" {{ $transaksi->status_pegawai == 'PNS' ? 'selected' : '' }}>PNS</option>
                                                                <option value="PPPK" {{ $transaksi->status_pegawai == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                                                <option value="CPNS" {{ $transaksi->status_pegawai == 'CPNS' ? 'selected' : '' }}>CPNS</option>
                                                                <option value="CPPPK" {{ $transaksi->status_pegawai == 'CPPPK' ? 'selected' : '' }}>CPPPK</option>
                                                                <option value="Honorer" {{ $transaksi->status_pegawai == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="kode{{ $transaksi->id }}" class="form-label">Kode Kendaraan</label>
                                                            <input list="listKodeEdit{{ $transaksi->id }}" class="form-control" id="kode{{ $transaksi->id }}" name="kode" value="{{ $transaksi->kendaraan->kode ?? '' }}" placeholder="Ketik atau pilih kode..." disabled>
                                                            <datalist id="listKodeEdit{{ $transaksi->id }}">
                                                                @foreach($data_kendaraan->unique('kode')->sortBy('kode') as $kendaraan)
                                                                    @if($kendaraan->kode)
                                                                        <option value="{{ $kendaraan->kode }}">{{ $kendaraan->kode }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="namaKendaraan{{ $transaksi->id }}" class="form-label">Nama Kendaraan</label>
                                                            <input list="listKendaraanEdit{{ $transaksi->id }}" class="form-control" id="namaKendaraan{{ $transaksi->id }}" name="nama_kendaraan" value="{{ $transaksi->kendaraan->namaKendaraan ?? '' }}" placeholder="Ketik atau pilih kendaraan..." required disabled>
                                                            <datalist id="listKendaraanEdit{{ $transaksi->id }}">
                                                                @foreach($data_kendaraan->unique('namaKendaraan')->sortBy('namaKendaraan') as $kendaraan)
                                                                    <option value="{{ $kendaraan->namaKendaraan }}">{{ $kendaraan->namaKendaraan }}</option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenisKendaraan{{ $transaksi->id }}" class="form-label">Jenis Kendaraan</label>
                                                            <input list="listJenisKendaraanEdit{{ $transaksi->id }}" class="form-control" id="jenisKendaraan{{ $transaksi->id }}" name="jenis_kendaraan" value="{{ $transaksi->kendaraan->jenisKendaraan ?? '' }}" placeholder="Ketik atau pilih jenis kendaraan..." required disabled>
                                                            <datalist id="listJenisKendaraanEdit{{ $transaksi->id }}">
                                                                @foreach($data_kendaraan->unique('jenisKendaraan')->sortBy('jenisKendaraan') as $kendaraan)
                                                                    @if($kendaraan->jenisKendaraan)
                                                                        <option value="{{ $kendaraan->jenisKendaraan }}">{{ $kendaraan->jenisKendaraan }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nomorPolisi{{ $transaksi->id }}" class="form-label">Nomor Polisi atau Ciri Kendaraan</label>
                                                            <input list="listNopolEdit{{ $transaksi->id }}" class="form-control" id="nomorPolisi{{ $transaksi->id }}" name="nomor_polisi" value="{{ $transaksi->kendaraan->nomorPolisi ?? '' }}" placeholder="Ketik atau pilih nomor polisi..." required disabled>
                                                            <datalist id="listNopolEdit{{ $transaksi->id }}">
                                                                @foreach($data_kendaraan->unique('nomorPolisi')->sortBy('nomorPolisi') as $kendaraan)
                                                                    @if($kendaraan->nomorPolisi)
                                                                        <option value="{{ $kendaraan->nomorPolisi }}">{{ $kendaraan->nomorPolisi }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="lokasiKendaraan{{ $transaksi->id }}" class="form-label">Lokasi Kendaraan</label>
                                                            <input list="listLokasiEdit{{ $transaksi->id }}" class="form-control" id="lokasiKendaraan{{ $transaksi->id }}" name="lokasi" value="{{ $transaksi->lokasi ?? '' }}" placeholder="Ketik atau pilih lokasi kendaraan..." required>
                                                            <datalist id="listLokasiEdit{{ $transaksi->id }}">
                                                                @foreach($data_kendaraan->unique('lokasi')->sortBy('lokasi') as $kendaraan)
                                                                    @if($kendaraan->lokasi)
                                                                        <option value="{{ $kendaraan->lokasi }}">{{ $kendaraan->lokasi }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenisTransaksi{{ $transaksi->id }}" class="form-label">Jenis Transaksi</label>
                                                            <select class="form-select" id="jenisTransaksi{{ $transaksi->id }}" name="jenisTransaksi" required disabled>
                                                                <option value="" disabled {{ !$transaksi->jenisTransaksi ? 'selected' : '' }}>Pilih jenis transaksi</option>
                                                                <option value="Keluar" {{ $transaksi->jenisTransaksi == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                                                                <option value="Masuk" {{ $transaksi->jenisTransaksi == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                                            </select>
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
                                                        <div class="mb-3">
                                                            <label for="alasan{{ $transaksi->id }}" class="form-label">Keterangan Transaksi</label>
                                                            <textarea class="form-control" id="alasan{{ $transaksi->id }}" name="alasan" rows="3" required>{{ $transaksi->alasan }}</textarea>
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
                                    <form action="{{ route('proses.transaksi_kendaraan.hapus', $transaksi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                                    <td colspan="12" class="text-center text-muted py-4">Belum ada data transaksi kendaraan.</td>
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
