<div class="row">
    <div class="col-md-1">
        @if (session('edit') == 'iya')
            @include('dashboard.pengguna.mutasi.edit')
        @else
            @include('dashboard.pengguna.mutasi.input')
        @endif
    </div>
    <div class="col-md-11 mt-5" style="padding-left: 150px">
        <h1 class="mb-4"><b>Mutasi</b></h1>
        <form class="mb-3" action="{{ Route('proses.mutasi_pilihan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="bulan" class="ms-2"><b>Bulan : </b></label>
                <select class="form-control-sm" id="bulan" name="bulan">
                    <option value="{{ $_SESSION['mutasi_bulan'] }}" name="{{ $_SESSION['mutasi_bulan'] }}">
                        @php
                            $cek_bulan = [];
                            $cek_bulan[] = $_SESSION['mutasi_bulan'];
                        @endphp
                        {{ $_SESSION['mutasi_bulan'] }}
                        @foreach ($mutasi_old as $item)
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
                    <option value="{{ $_SESSION['mutasi_tahun'] }}" name="{{ $_SESSION['mutasi_tahun'] }}">
                        @php
                            $cek_tahun = [];
                            $cek_tahun[] = $_SESSION['mutasi_tahun'];
                        @endphp
                        {{ $_SESSION['mutasi_tahun'] }}
                        @foreach ($mutasi_old as $item)
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
        <p style="font-size: 14px" class="mb-4"> <b>Keterangan : <i></b>
            Bulan :
            {{ $_SESSION['mutasi_bulan'] }}, Tahun
            :
            {{ $_SESSION['mutasi_tahun'] }}</i></p>

        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Asset</th>
                    <th>Kode FA</th>
                    <th>Nama Barang</th>
                    <th>Outlet Actual</th>
                    <th>Type Barang</th>
                    <th>Location</th>
                    <th>Jabatan</th>
                    <th>User Domain</th>
                    <th>NIK</th>
                    <th>Komputer Nama</th>
                    <th>IP Address</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th>Serial Number</th>
                    <th>Sophos</th>
                    <th>Landesk</th>
                    <th>Mutasi Asal</th>
                    <th>Mutasi Tujuan</th>
                    <th>Keterangan Mutasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datanya as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->asset }}</td>
                        <td>{{ $data->kode_fa_fams }}</td>
                        <td>{{ $data->nama_barang }}</td>
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
                        <td>{{ $data->mutasi_asal }}</td>
                        <td>{{ $data->mutasi_tujuan }}</td>
                        <td>{{ $data->keterangan_mutasi }}</td>

                        <td>
                            <a href="{{ route('proses.pengguna.mutasi.edit', ['id' => $data->id]) }}"
                                class="btn btn-warning">Edit</a>
                            |
                            <a href="{{ route('proses.pengguna.mutasi.delete', ['id' => $data->id]) }}"
                                class="btn btn-danger">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $datanya->links('pagination::bootstrap-4') }}
    </div>
</div>
