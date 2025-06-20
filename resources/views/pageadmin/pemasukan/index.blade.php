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
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemasukan</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Pemasukan</h6>
            <hr />
            
            <!-- Filter Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bx bx-filter-alt me-2"></i>Filter Data</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('pemasukan.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="">Semua Bulan</option>
                                @foreach($bulanList as $key => $bulan)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('pemasukan.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Total Pemasukan Keseluruhan</th>
                                <td><span class="badge bg-success">Rp.
                                        {{ number_format($pemasukans->sum('total_harga'), 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Filter Info -->
            @if(request('bulan') || request('tahun'))
                <div class="alert alert-info mb-3">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Filter Aktif:</strong>
                    @if(request('bulan') && request('tahun'))
                        {{ $bulanList[request('bulan')] }} {{ request('tahun') }}
                    @elseif(request('tahun'))
                        Tahun {{ request('tahun') }}
                    @endif
                    <a href="{{ route('pemasukan.index') }}" class="float-end text-decoration-none">
                        <i class="bx bx-x"></i> Hapus Filter
                    </a>
                </div>
            @endif
            
            <!-- Data Count Info -->
            <div class="alert alert-light mb-3">
                <i class="bx bx-data me-2"></i>
                <strong>Menampilkan {{ $pemasukans->count() }} data pemasukan</strong>
                @if(request('bulan') || request('tahun'))
                    berdasarkan filter yang dipilih
                @endif
            </div>
            
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('pemasukan.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemasukans as $index => $p)
                                    <tr>
                                        <td>{{ $p->keterangan }}</td>
                                        <td>Rp. {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                                        <td>{{ $p->jumlah }} ({{ $p->master_satuan->nama_satuan }})</td>
                                        <td>Rp. {{ number_format($p->harga_satuan * $p->jumlah, 0, ',', '.') }}</td>
                                        <td>
                                            @if($p->gambar)
                                                <img src="{{ asset('uploads/pemasukan/' . $p->gambar) }}" alt="Gambar" style="width: 100px; height: 100px;">
                                            @else
                                                <img src="https://png.pngtree.com/png-vector/20190820/ourmid/pngtree-no-image-vector-illustration-isolated-png-image_1694547.jpg" alt="No Image" style="width: 100px; height: 100px;">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pemasukan.edit', $p->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('pemasukan.destroy', $p->id) }}" method="POST"
                                                style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
