<li class="nav-item">
    <a href="{{ route('dashboard', ['menu' => 'dashboard']) }}" class="nav-link text-white">
        @if ($menu == 'dashboard')
            <b><i>Dashboard</i></b>
        @else
            Dashboard
        @endif
    </a>
</li>
@if ($posisi == 'admin')
    <li class="nav-item">
        <a href="{{ route('dashboard', ['menu' => 'user_manajemen']) }}" class="nav-link text-white">
            @if ($menu == 'user_manajemen')
                <b><i>Manajemen User</i></b>
            @else
                Manajemen User
            @endif
        </a>
    </li>
@endif
<li class="nav-item">
    <a href="{{ route('dashboard', ['menu' => 'data_barang']) }}" class="nav-link text-white">
        @if ($menu == 'data_barang')
            <b><i>Data Barang</i></b>
        @else
            Data Barang
        @endif
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard', ['menu' => 'mutasi']) }}" class="nav-link text-white">
        @if ($menu == 'mutasi')
            <b><i>Mutasi</i></b>
        @else
            Mutasi
        @endif
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard', ['menu' => 'penghapusan']) }}" class
    ="nav-link text-white">
        @if ($menu == 'penghapusan')
            <b><i>Penghapusan</i></b>
        @else
            Penghapusan
        @endif
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('dashboard', ['menu' => 'export_to_excel']) }}" class="nav-link text-white">
        @if ($menu == 'export_to_excel')
            <b><i>Export to Excel</i></b>
        @else
            Export to Excel
        @endif
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('logout') }}" class="nav-link text-white">Logout</a>
</li>
