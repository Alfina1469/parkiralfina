<ul class="nav">
    @if(session()->has('user_logged_in'))
        @php
            $role = session('user_logged_in.role');
        @endphp

        {{-- 1. TAMPILAN KHUSUS ADMIN --}}
        @if($role === 'Admin')
            <div class="sidebar-heading">Menu Admin</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.user.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Kelola User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.area.index') }}">
                    <i class="fas fa-fw fa-map-marker-alt"></i>
                    <span>Area Parkir</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.kendaraan.index') }}">
                    <i class="fas fa-fw fa-car"></i>
                    <span>Jenis Kendaraan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.tarif.index') }}">
                    <i class="fas fa-fw fa-money-bill"></i>
                    <span>Tarif Parkir</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.log.index') }}">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            </li>

        {{-- 2. TAMPILAN KHUSUS PETUGAS --}}
        @elseif($role === 'Petugas')
            <div class="sidebar-heading">Menu Operasional</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('petugas.transaksi.index') }}">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Transaksi Parkir</span>
                </a>
            </li>

        {{-- 3. TAMPILAN KHUSUS OWNER --}}
        @elseif($role === 'Owner')
            <div class="sidebar-heading">Menu Eksekutif</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('owner.laporan.index') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Laporan & Ringkasan</span>
                </a>
            </li>
        @endif

        <hr class="sidebar-divider">

        <!-- TOMBOL LOGOUT (SAMA UNTUK SEMUA ROLE) -->
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-left w-100 border-0">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    @endif
</ul>