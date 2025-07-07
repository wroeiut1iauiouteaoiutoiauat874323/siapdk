<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary mb-0">Data Pegawai</h4>
        <div class="d-flex flex-column align-items-end me-3" style="min-width: 320px;">
            <form action="{{ route('dashboard.search', ['menu' => 'dpegawai']) }}" method="GET" class="d-flex align-items-center w-100 mb-1">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari pegawai..." value="" style="min-width:180px;" autofocus>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <small class="text-muted">Cari berdasarkan nama, NIP, jabatan, atau email</small>
        </div>
        <a href="#" class="btn btn-success shadow-sm px-4 py-2" id="btnTambahPegawai">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pegawai
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Modal Tambah Pegawai -->
    <div class="modal fade" id="modalTambahPegawai" tabindex="-1" aria-labelledby="modalTambahPegawaiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('proses.data_pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahPegawaiLabel">Tambah Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required placeholder="Masukkan NIP">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required placeholder="Masukkan jabatan">
                        </div>
                        <div class="mb-3">
                            <label for="statusPegawai" class="form-label">Status Akun</label>
                            <select class="form-select" id="statusPegawai" name="statusPegawai" required>
                                <option value="umum">Umum</option>
                                <option value="bukanumum" selected>Pegawai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password" placeholder="Silahkan isikan password akun">
                            <small class="text-muted">Isi dengan minimal 8 karakter ada huruf kecil, kapital, angka dan simbol</small>
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

    <script>
        document.getElementById('btnTambahPegawai').addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('modalTambahPegawai'));
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
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">NIP</th>
                                    <th scope="col" class="text-center">Jabatan</th>
                                    <th scope="col" style="width: 135px; padding-left:50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data_user as $pegawai)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($data_user->currentPage() - 1) * $data_user->perPage() }}</td>
                                    <td class="text-center">{{ $pegawai->namaPegawai }}</td>
                                    <td class="text-center">{{ $pegawai->nipPegawai }}</td>
                                    <td class="text-center">{{ $pegawai->jabatan }}</td>
                                    <td>
                                       <!-- Tombol Edit -->
                                        <a href="#" class="btn btn-warning btn-sm btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditPegawai{{ $pegawai->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Modal Edit Pegawai -->
                                        <div class="modal fade" id="modalEditPegawai{{ $pegawai->id }}" tabindex="-1" aria-labelledby="modalEditPegawaiLabel{{ $pegawai->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('proses.data_pegawai.edit', $pegawai->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalEditPegawaiLabel{{ $pegawai->id }}">Edit Pegawai</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="nama{{ $pegawai->id }}" class="form-label">Nama</label>
                                                                <input type="text" class="form-control" id="nama{{ $pegawai->id }}" name="nama" value="{{ $pegawai->namaPegawai }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="nip{{ $pegawai->id }}" class="form-label">NIP</label>
                                                                <input type="text" class="form-control" id="nip{{ $pegawai->id }}" name="nip" value="{{ $pegawai->nipPegawai }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="jabatan{{ $pegawai->id }}" class="form-label">Jabatan</label>
                                                                <input type="text" class="form-control" id="jabatan{{ $pegawai->id }}" name="jabatan" value="{{ $pegawai->jabatan }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="statusPegawai{{ $pegawai->id }}" class="form-label">Status Akun</label>
                                                                <select class="form-select" id="statusPegawai{{ $pegawai->id }}" name="statusPegawai" required>
                                                                    <option value="umum" {{ $pegawai->status == 'umum' ? 'selected' : '' }}>Umum</option>
                                                                    <option value="bukanumum" {{ $pegawai->status == 'bukanumum' ? 'selected' : '' }}>Pegawai</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password{{ $pegawai->id }}" class="form-label">Password</label>
                                                                <input type="text" class="form-control" id="password{{ $pegawai->id }}" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                                <small class="text-muted">Isi jika ingin mengubah password. Minimal 8 karakter ada huruf kecil, kapital, angka dan simbol</small>
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

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('proses.data_pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
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
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data pegawai.</td>
                                </tr>
                                @endforelse
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{ $data_user->links() }}
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
