<div class="row mt-5 justify-content-end">
    <div class="col-md-12">
        <h1 class="mb-4"><b>Data Barang</b></h1>
        <form class="mb-3" action="{{ Route('proses.data_barang.pilihan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="area"><b>Pilih Area : </b></label>
                <select class="form-control-sm" id="area" name="area">
                    <option value="{{ $_SESSION['data_barang_area'] }}" name="{{ $_SESSION['data_barang_area'] }}">
                        @php
                            $cek_area = [];
                            $cek_area[] = $_SESSION['data_barang_area'];
                        @endphp
                        {{ $_SESSION['data_barang_area'] }}
                        @foreach ($data_user as $item)
                            @if (!in_array($item->area, $cek_area))
                                @php
                                    $cek_area[] = $item->area;
                                @endphp
                    <option value="{{ $item->area }}" name="{{ $item->area }}">{{ $item->area }} </option>
                    @endif
                    @if (!in_array('all', $cek_area))
                        @php
                            $cek_area[] = 'all';
                        @endphp
                        <option value="all" name="all">all</option>
                    @endif
                    @endforeach
                </select>
                <label for="bulan" class="ms-2"><b>Bulan : </b></label>
                <select class="form-control-sm" id="bulan" name="bulan">
                    <option value="{{ $_SESSION['data_barang_bulan'] }}" name="{{ $_SESSION['data_barang_bulan'] }}">
                        @php
                            $cek_bulan = [];
                            $cek_bulan[] = $_SESSION['data_barang_bulan'];
                        @endphp
                        {{ $_SESSION['data_barang_bulan'] }}
                        @foreach ($data_barang_old as $item)
                            @if (!in_array($item->bulan, $cek_bulan))
                                @php
                                    $cek_bulan[] = $item->bulan;
                                @endphp
                    <option value="{{ $item->bulan }}" name="{{ $item->bulan }}">{{ $item->bulan }} </option>
                    @endif
                    @if (!in_array('all', $cek_bulan))
                        @php
                            $cek_bulan[] = 'all';
                        @endphp
                        <option value="all">all</option>
                    @endif
                    @endforeach
                </select>
                <label for="tahun" class="ms-2"><b>Tahun : </b></label>
                <select class="form-control-sm" id="tahun" name="tahun">
                    <option value="{{ $_SESSION['data_barang_tahun'] }}" name="{{ $_SESSION['data_barang_tahun'] }}">
                        @php
                            $cek_tahun = [];
                            $cek_tahun[] = $_SESSION['data_barang_tahun'];
                        @endphp
                        {{ $_SESSION['data_barang_tahun'] }}
                        @foreach ($data_barang_old as $item)
                            @if (!in_array($item->tahun, $cek_tahun))
                                @php
                                    $cek_tahun[] = $item->tahun;
                                @endphp
                    <option value="{{ $item->tahun }}" name="{{ $item->tahun }}">{{ $item->tahun }} </option>
                    @endif
                    @if (!in_array('all', $cek_tahun))
                        @php
                            $cek_tahun[] = 'all';
                        @endphp
                        <option value="all">all</option>
                    @endif
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn ms-3"><b>Submit</b></button>
            </div>
        </form>
        <p style="font-size: 14px" class="mb-4"> <b>Keterangan : <i></b>Area : {{ $_SESSION['data_barang_area'] }},
            Bulan :
            {{ $_SESSION['data_barang_bulan'] }}, Tahun
            :
            {{ $_SESSION['data_barang_tahun'] }}</i></p>

        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Perolehan</th>
                    <th>Asset</th>
                    <th>Kode Fa Fams</th>
                    <th>Nama Barang</th>
                    <th>Outlet Pencatatan</th>
                    <th>Outlet Actual</th>
                    <th>Type Barang</th>
                    <th>Location</th>
                    <th>Jabatan</th>
                    <th>Nama User</th>
                    <th>NIK</th>
                    <th>Komputer Nama</th>
                    <th>IP Address</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th>Serial Number</th>
                    <th>Sophos</th>
                    <th>Landesk</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datanya as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->tanggal_perolehan }}</td>
                        <td>{{ $data->asset }}</td>
                        <td>{{ $data->kode_fa_fams }}</td>
                        <td>{{ $data->nama_barang }}</td>
                        <td>{{ $data->outlet_pencatatan }}</td>
                        <td>{{ $data->outlet_actual }}</td>
                        <td>{{ $data->type_barang }}</td>
                        <td>{{ $data->location }}</td>
                        <td>{{ $data->jabatan }}</td>
                        <td>{{ $data->nama_user }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->komputer_nama }}</td>
                        <td>{{ $data->ip_address }}</td>
                        <td>{{ $data->kondisi }}</td>
                        <td>{{ $data->keterangan }}</td>
                        <td>{{ $data->serial_number }}</td>
                        <td>{{ $data->sophos }}</td>
                        <td>{{ $data->landesk }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $datanya->links('pagination::bootstrap-4') }}
    </div>
</div>
