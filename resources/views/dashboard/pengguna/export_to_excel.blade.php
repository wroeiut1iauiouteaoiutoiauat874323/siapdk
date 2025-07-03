<div class="container">
    <div class="row text-center mt-5">
        <h1><b>Export to Excel</b></h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-title text-center">
                    <h5 class="mb-3 mt-3">Data Barang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('proses.export.data_barang') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="bulan" class="ms-2">Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">
                                <option value="{{ $_SESSION['data_barang_bulan'] }}"
                                    name="{{ $_SESSION['data_barang_bulan'] }}">
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
                                <option value="{{ $item->bulan }}" name="{{ $item->bulan }}">{{ $item->bulan }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_bulan))
                                    @php
                                        $cek_bulan[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="tahun" class="ms-2">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="{{ $_SESSION['data_barang_tahun'] }}"
                                    name="{{ $_SESSION['data_barang_tahun'] }}">
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
                                <option value="{{ $item->tahun }}" name="{{ $item->tahun }}">{{ $item->tahun }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_tahun))
                                    @php
                                        $cek_tahun[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="export_data_barang_print"
                                class="btn btn-primary w-50 mt-3"><b>Print</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-title text-center">
                    <h5 class="mb-3 mt-3">Mutasi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('proses.export.mutasi') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="bulan" class="ms-2">Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">
                                <option value="{{ $_SESSION['mutasi_bulan'] }}"
                                    name="{{ $_SESSION['mutasi_bulan'] }}">
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
                                <option value="{{ $item->bulan }}" name="{{ $item->bulan }}">{{ $item->bulan }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_bulan))
                                    @php
                                        $cek_bulan[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="tahun" class="ms-2">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="{{ $_SESSION['mutasi_tahun'] }}"
                                    name="{{ $_SESSION['mutasi_tahun'] }}">
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
                                <option value="{{ $item->tahun }}" name="{{ $item->tahun }}">{{ $item->tahun }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_tahun))
                                    @php
                                        $cek_tahun[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="export_mutasi_print"
                                class="btn btn-primary w-50 mt-3"><b>Print</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-title text-center">
                    <h5 class="mb-3 mt-3">Penghapusan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('proses.export.penghapusan') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="bulan" class="ms-2">Bulan</label>
                            <select class="form-control" id="bulan" name="bulan">
                                <option value="{{ $_SESSION['penghapusan_bulan'] }}"
                                    name="{{ $_SESSION['penghapusan_bulan'] }}">
                                    @php
                                        $cek_bulan = [];
                                        $cek_bulan[] = $_SESSION['penghapusan_bulan'];
                                    @endphp
                                    {{ $_SESSION['penghapusan_bulan'] }}
                                    @foreach ($penghapusan_old as $item)
                                        @if (!in_array($item->bulan, $cek_bulan))
                                            @php
                                                $cek_bulan[] = $item->bulan;
                                            @endphp
                                <option value="{{ $item->bulan }}" name="{{ $item->bulan }}">{{ $item->bulan }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_bulan))
                                    @php
                                        $cek_bulan[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="tahun" class="ms-2">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="{{ $_SESSION['penghapusan_tahun'] }}"
                                    name="{{ $_SESSION['penghapusan_tahun'] }}">
                                    @php
                                        $cek_tahun = [];
                                        $cek_tahun[] = $_SESSION['penghapusan_tahun'];
                                    @endphp
                                    {{ $_SESSION['penghapusan_tahun'] }}
                                    @foreach ($penghapusan_old as $item)
                                        @if (!in_array($item->tahun, $cek_tahun))
                                            @php
                                                $cek_tahun[] = $item->tahun;
                                            @endphp
                                <option value="{{ $item->tahun }}" name="{{ $item->tahun }}">{{ $item->tahun }}
                                </option>
                                @endif
                                @if (!in_array('all', $cek_tahun))
                                    @php
                                        $cek_tahun[] = 'all';
                                    @endphp
                                    <option value="all">all</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-50 mt-3"
                                name="export_penghapusan_print"><b>Print</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
