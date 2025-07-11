<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KATALIS – Kendaraan dan Barang Transmisi Layanan Informasi Sistematis</title>
    <link rel="stylesheet" href="{{ asset('bt/css/bootstrap.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #305ba9 0%, #6a82fb 100%);
            min-height: 100vh;
        }
        .login-card {
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            background: rgba(255,255,255,0.95);
        }
        .login-header {
            background: linear-gradient(90deg, #305ba9 60%, #6a82fb 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
        }
        .form-control:focus {
            border-color: #305ba9;
            box-shadow: 0 0 0 0.2rem rgba(48,91,169,.25);
        }
        .btn-primary {
            background: linear-gradient(90deg, #305ba9 60%, #6a82fb 100%);
            border: none;
        }
        .btn-primary:hover {
            background: #305ba9;
        }
        .alert-danger {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-7">
                <div class="card login-card mt-1">
                    <div class="card-header login-header pt-4 pb-4 text-center">
                        <h4 class="mb-0">Kendaraan dan Barang Transmisi Layanan Informasi Sistematis</h4>
                    </div>
                    <div class="card-body px-5 py-4">
                        {{-- tampilkan error jika ada --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('proses.login') }}" method="post">
                            @csrf
                            <div class="form-group mt-4">
                                <label for="nipPegawai" class="fw-bold">NIP</label>
                                <input type="text" name="nipPegawai" id="nipPegawai" class="form-control" required autofocus
                                    placeholder="Masukkan NIP Anda">
                            </div>
                            <div class="form-group mt-4">
                                <label for="password" class="fw-bold">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    placeholder="Masukkan password Anda">
                            </div>
                            <div class="form-group mt-5 mb-2 text-center">
                                <button type="submit" class="btn btn-primary btn-block w-50 py-2 fs-5">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center text-white mt-4" style="opacity: 0.7;">
                    &copy; {{ date('Y') }} KATALIS
                </div>
            </div>
        </div>
    </div>
</body>

</html>
