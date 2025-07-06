<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Kendaraan</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'kendaraan']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari kendaraan..." value="{{ request('search') }}" style="min-width:180px;" autofocus>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode, nama, jenis, nomor polisi, lokasi, atau ciri kendaraan</small>
        </div>

    </div>

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
                                    <th scope="col" class="text-center">Lokasi</th>
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
                                    <td class="text-center">{{ $kendaraan->lokasi }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data kendaraan.</td>
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
