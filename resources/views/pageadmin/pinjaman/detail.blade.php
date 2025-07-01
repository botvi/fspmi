@extends('template-admin.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('pinjaman.index') }}">Pinjaman</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Pinjaman - {{ $member->nama }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Detail Pinjaman - {{ $member->nama }}</h6>
            <hr />
          
            <!-- Ringkasan Pinjaman -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pinjaman</h5>
                            <h3>Rp. {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Angsuran</h5>
                            <h3>Rp. {{ number_format($totalAngsuran, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card {{ $sisaPinjaman > 0 ? 'bg-warning' : 'bg-info' }} text-white">
                        <div class="card-body">
                            <h5 class="card-title">Sisa Pinjaman</h5>
                            <h3>Rp. {{ number_format($sisaPinjaman, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Jumlah Transaksi</h5>
                            <h3>{{ $pinjaman->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Transaksi Pinjaman -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Detail Transaksi Pinjaman</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Pinjaman</th>
                                    <th>Total Angsuran</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pinjaman as $index => $p)
                                    @php
                                        $totalAngsuranPinjaman = $p->angsurans->sum('jumlah_angsuran');
                                        $sisaPinjaman = $p->jumlah_pinjaman - $totalAngsuranPinjaman;
                                        $status = $sisaPinjaman > 0 ? 'Belum Lunas' : 'Lunas';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $p->keterangan }}</td>
                                        <td>Rp. {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($totalAngsuranPinjaman, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($sisaPinjaman, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $status == 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail Angsuran -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Angsuran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Angsuran</th>
                                    <th>Keterangan Pinjaman</th>
                                    <th>Jumlah Angsuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allAngsurans = collect();
                                    foreach ($pinjaman as $p) {
                                        foreach ($p->angsurans as $angsuran) {
                                            $allAngsurans->push([
                                                'tanggal' => $angsuran->tanggal_angsuran,
                                                'keterangan' => $p->keterangan,
                                                'jumlah' => $angsuran->jumlah_angsuran
                                            ]);
                                        }
                                    }
                                    $allAngsurans = $allAngsurans->sortByDesc('tanggal');
                                @endphp
                                
                                @if($allAngsurans->count() > 0)
                                    @foreach ($allAngsurans as $index => $angsuran)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($angsuran['tanggal'])->format('d/m/Y') }}</td>
                                            <td>{{ $angsuran['keterangan'] }}</td>
                                            <td>Rp. {{ number_format($angsuran['jumlah'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada angsuran</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection 