<table class="table table-borderless mt-5" style="width: 400px">
    <thead>
        <tr class="text-center align-text-center" style="height: 80px">
            <th>
                <h3>Edit</h3>
            </th>
        </tr>
    </thead>
    <tbody>
        <form action="{{ route('proses.pengguna.penghapusan.update', ['id' => session('useredit')->id]) }}"
            method="POST">
            @csrf

            @if ($errors->any())
                <tr>
                    <td>
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
            @endif
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="tanggal_perolehan">Tanggal Perolehan</label>
                        <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" class="form-control"
                            value="{{ session('useredit')->tanggal_perolehan }}" autofocus>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="nama_barang_asset">Asset</label>
                        <input type="text" name="nama_barang_asset" id="nama_barang_asset" class="form-control"
                            value="{{ session('useredit')->asset }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="kode_fa_fams">Kode FA FAMS</label>
                        <input type="text" name="kode_fa_fams" id="kode_fa_fams" class="form-control"
                            value="{{ session('useredit')->kode_fa_fams }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                            value="{{ session('useredit')->nama_barang }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="outlet_pencatatan">Outlet Pencatatan</label>
                        <input type="text" name="outlet_pencatatan" id="outlet_pencatatan" class="form-control"
                            value="{{ session('useredit')->outlet_pencatatan }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="outlet_actual">Outlet Actual</label>
                        <input type="text" name="outlet_actual" id="outlet_actual" class="form-control"
                            value="{{ session('useredit')->outlet_actual }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="type_barang">Type Barang</label>
                        <input type="text" name="type_barang" id="type_barang" class="form-control"
                            value="{{ session('useredit')->type_barang }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control"
                            value="{{ session('useredit')->location }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control"
                            value="{{ session('useredit')->jabatan }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="nama_user">Nama User</label>
                        <input type="text" name="nama_user" id="nama_user" class="form-control"
                            value="{{ session('useredit')->nama_user }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control"
                            value="{{ session('useredit')->nik }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="nama_komputer">Nama Komputer</label>
                        <input type="text" name="nama_komputer" id="nama_komputer" class="form-control"
                            value="{{ session('useredit')->komputer_nama }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="ip_address">IP Address</label>
                        <input type="text" name="ip_address" id="ip_address" class="form-control"
                            value="{{ session('useredit')->ip_address }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="kondisi">Kondisi</label>
                        <input type="text" name="kondisi" id="kondisi" class="form-control"
                            value="{{ session('useredit')->kondisi }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control"
                            value="{{ session('useredit')->keterangan }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-control"
                            value="{{ session('useredit')->serial_number }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group mx-3 d-flex">
                        <label for="sophos">Shopos: </label>
                        <select name="sophos" class="form-control-sm me-3 ms-1" id="sophos">
                            @if (session('useredit')->sophos == 'Iya')
                                <option value="Iya">Iya</option>
                                <option value="Tidak">Tidak</option>
                            @elseif (session('useredit')->sophos == 'Tidak')
                                <option value="Tidak">Tidak</option>
                                <option value="Iya">Iya</option>
                            @else
                                <option value="Iya">Iya</option>
                                <option value="Tidak">Tidak</option>
                            @endif
                        </select>
                        <label for="landesk">Landesk: </label>
                        <select name="landesk" class="form-control-sm me-3 ms-1" id="landesk">
                            @if (session('useredit')->landesk == 'Iya')
                                <option value="Iya">Iya</option>
                                <option value="Tidak">Tidak</option>
                            @elseif (session('useredit')->landesk == 'Tidak')
                                <option value="Tidak">Tidak</option>
                                <option value="Iya">Iya</option>
                            @else
                                <option value="Iya">Iya</option>
                                <option value="Tidak">Tidak</option>
                            @endif
                        </select>
                    </div>
                </td>
            </tr>

            <tr class="text-center">
                <td>
                    <button type="submit" class="btn btn-danger w-25 mb-2" name="bataltombol">Batal</button>
                    <button type="submit" class="btn btn-primary w-50 mb-2" name="submittombol">Submit</button>
                </td>
            </tr>
        </form>
    </tbody>
</table>
