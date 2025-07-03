<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIAPDK - Login</title>
    <link rel="stylesheet" href="{{ asset('bt/css/bootstrap.css') }}">
</head>

<body class="" style="background-color: #305ba9;">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 10%;">
            {{-- buatlah jika error dalam validation --}}
            @if ($errors->any())
                <div class="col-md-6">
                    <div class="card mt-4">
                        <div class="card-header pt-3 pb-3 text-center">
                            <h4>Sistem Informasi Aplikasi Digitalisasi Kendaraan</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
            @else
                <div class="col-md-6 mt-5">
                    <div class="card mt-4">
                        <div class="card-header pt-3 pb-3 text-center">
                            <h4>Sistem Informasi Aplikasi Digitalisasi Kendaraan</h4>
                        </div>
                        <div class="card-body">
            @endif
            <form action="{{ route('proses.login') }}" method="post">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="form-group mt-3">
                            <label for="NIP">NIP</label>
                            <input type="text" name="NIP" id="NIP" class="form-control" required autofocus
                                placeholder="Masukkan NIP Anda">
                        </div>
                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                placeholder="Masukkan password Anda">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-5 mb-4 text-center">
                    <button type="submit" class="btn btn-primary btn-block w-25">Login</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>

</body>

</html>
