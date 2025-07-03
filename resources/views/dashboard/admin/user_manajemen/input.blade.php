<div class="card mt-5">
    <div class="card-header bg-dark text-white">
        <h5 class="text-center">Tambah User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('proses.admin.user_manajemen.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" autofocus>
            </div>
            <div class="form-group mt-2">
                <label for="nik">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="posisi">Posisi</label>
                <select name="posisi" id="posisi" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="pengguna">Pengguna</option>
                </select>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="area">Area: </label>
                <select name="area" class="form-control-sm" id="subarea">
                    <option value="Cabang">Cabang</option>
                    <option value="Satelit">Satelit</option>
                    <option value="Warehouse">Warehouse</option>
                </select>
                <input type="text" name="daerah" id="daerah" class="form-control-sm w-50"
                    placeholder="Nama daerah">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-3 w-100" name="tambah">Tambah</button>
            </div>
        </form>
    </div>
</div>
