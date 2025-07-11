<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Barang</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'barang']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..." value="" style="min-width:180px;" autofocus>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
            </form>
            <small class="text-muted">Cari berdasarkan kode, kategori, lokasi, atau nama barang</small>
        </div>
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
                                    <th scope="col" class="text-center">Nama Barang</th>
                                    <th scope="col" class="text-center">Kategori</th>
                                    <th scope="col" class="text-center">Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($datanya as $barang)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($datanya->currentPage() - 1) * $datanya->perPage() }}</td>
                                    <td class="text-center">{{ $barang->kode }}</td>
                                    <td class="text-center">{{ $barang->namaBarang }}</td>
                                    <td class="text-center">{{ $barang->jenisBarangPersediaan }}</td>
                                    <td class="text-center">{{ $barang->lokasi }}</td>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data barang.</td>
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
