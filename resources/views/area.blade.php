<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pilih Area</title>
    <link rel="stylesheet" href="{{ asset('bt/css/bootstrap.css') }}">
</head>

<body class="bg-warning">
    <div class="container">
        <div class="row justify-content-center mt-5">
            {{-- buatlah jika error dalam validation --}}
            @if ($errors->any())
                <div class="col-md-5">
                    <div class="card mt-4">
                        <div class="card-header bg-dark text-white text-center">
                            <h4>Pilih Area</h4>
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
                            <div class="col-md-5 mt-5">
                                <div class="card mt-4">
                                    <div class="card-header bg-dark text-white text-center">
                                        <h4>Pilh Area</h4>
                                    </div>
                                    <div class="card-body">
            @endif
            <form action="{{ route('proses.login.lempar') }}" method="post">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="form-group mt-3">
                            <label for="area">Area</label>
                            <select name="area" id="area" class="form-control">
                                @foreach ($data_area as $area)
                                    <option value="{{ $area->area_user }}">{{ $area->area_user }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-5 mb-4 text-center">
                    <button type="submit" class="btn btn-dark btn-block w-25">Login</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>

</body>

</html>
