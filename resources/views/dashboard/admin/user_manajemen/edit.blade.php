<div class="card mt-5">
    <div class="card-header bg-dark text-white">
        <h5 class="text-center">Edit User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('proses.admin.user_manajemen.update', ['id' => session('useredit')->id]) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control"
                    value="{{ session('useredit')->nama }}" autofocus>
            </div>
            <div class="form-group mt-2">
                <label for="nik">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control"
                    value="{{ session('useredit')->nik }}">
            </div>
            <div class="form-group mt-2">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="posisi">Posisi</label>
                <select name="posisi" id="posisi" class="form-control">
                    @if (session('useredit')->posisi == 'admin')
                        <option value="admin">Admin</option>
                        <option value="pengguna">Pengguna</option>
                    @else
                        <option value="pengguna">Pengguna</option>
                        <option value="admin">Admin</option>
                    @endif
                </select>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="area">Area: </label>
                <select name="area" class="form-control-sm" id="subarea">
                    @if (session('katapertama') == 'Cabang')
                        <option value="Cabang">Cabang</option>
                        <option value="Satelit">Satelit</option>
                        <option value="Warehouse">Warehouse</option>
                    @elseif (session('katapertama') == 'Satelit')
                        <option value="Satelit">Satelit</option>
                        <option value="Cabang">Cabang</option>
                        <option value="Warehouse">Warehouse</option>
                    @else
                        <option value="Warehouse">Warehouse</option>
                        <option value="Cabang">Cabang</option>
                        <option value="Satelit">Satelit</option>
                    @endif
                </select>
                <input type="text" name="daerah" id="daerah" class="form-control-sm w-50"
                    placeholder="Nama daerah" value="{{ session('area') }}">
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-danger mt-3 w-25" name="tombolbatal" value="Batal">
                <input type="submit" class="btn btn-primary mt-3 w-50" name="tomboledit" value="Edit">
            </div>
        </form>
    </div>
</div>
