<ul class="metismenu" id="menu">
    <li class="menu-label">DASHBOARD</li>
    <li>
        <a href="/dashboard">
            <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
            <div class="menu-title">DASHBOARD</div>
        </a>
    </li>

    <li>
        <a href="/profil-admin">
            <div class="parent-icon"><i class='bx bx-user'></i></div>
            <div class="menu-title">PROFIL</div>
        </a>
    </li>

    @if (Auth::user()->role == 'admin')
    <li>
        <a href="/manemegen_anggota">
            <div class="parent-icon"><i class='bx bx-user'></i></div>
            <div class="menu-title">MANAJEMEN ANGGOTA</div>
        </a>
    </li>
    <li class="menu-label">DATA</li>
    <li>
        <a href="/master_satuan">
            <div class="parent-icon"><i class='bx bx-list-check'></i></div>
            <div class="menu-title">MASTER SATUAN</div>
        </a>
    </li>
    <li>
        <a href="/pemasukan">
            <div class="parent-icon"><i class='bx bx-up-arrow-alt text-success'></i></div>
            <div class="menu-title">PEMASUKAN</div>
        </a>
    </li>
    <li>
        <a href="/pengeluaran">
            <div class="parent-icon"><i class='bx bx-down-arrow-alt text-danger'></i></div>
            <div class="menu-title">PENGELUARAN</div>
        </a>
    </li>
    <li class="menu-label">LAPORAN</li>
    <li>
        <a href="{{ route('pembagian_saldo') }}">
            <div class="parent-icon"><i class='bx bx-dollar-circle'></i></div>
            <div class="menu-title">PEMBAGIAN SALDO</div>
        </a>
    </li>
    @endif

    @if (Auth::user()->role == 'member')
    <li>
        <a href="/pembagian-saldo-member">
            <div class="parent-icon"><i class='bx bx-dollar-circle'></i></div>
            <div class="menu-title">PEMBAGIAN SALDO</div>
        </a>
    </li>
    @endif
</ul>
