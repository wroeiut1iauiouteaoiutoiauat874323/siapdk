<div class="row">
    <div class="col-md-4">
        <?php
            if ($_SESSION['edit'] == 'iya') {
        ?>
        @include('dashboard.admin.user_manajemen.edit')
        <?php
            } else {
        ?>
        @include('dashboard.admin.user_manajemen.input')
        <?php
            }
        ?>
    </div>

    <div class="col-md-8">
        <div class="card mt-5">
            <h4 class="text-center mt-4">Manajemen User</h4>
            <div class="card-body">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Posisi</th>
                            <th>Area</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datanya as $user)
                            <tr>
                                <td style="height: 8px; font-size: 11pt">{{ $loop->iteration }}</td>
                                <td style="height: 8px; font-size: 11pt">{{ $user->nama }}</td>
                                <td style="height: 8px; font-size: 11pt">{{ $user->nik }}</td>
                                <td style="height: 8px; font-size: 11pt">{{ $user->posisi }}</td>
                                <td style="height: 8px; font-size: 11pt">{{ $user->area }}</td>
                                <td>
                                    <a href="{{ route('proses.admin.user_manajemen.edit', $user->id) }}"
                                        class="btn btn-warning">Edit</a>
                                    <form action="{{ route('proses.admin.user_manajemen.delete', $user->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $datanya->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>
