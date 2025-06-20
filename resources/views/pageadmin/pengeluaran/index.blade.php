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
                            <li class="breadcrumb-item active" aria-current="page">Pengeluaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Pengeluaran</h6>
            <hr />
            
            <!-- Filter Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('pengeluaran.index') }}" class="row g-3">
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
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Reset</a>
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
                                <th>Total Pengeluaran Keseluruhan</th>
                                <td><span class="badge bg-danger">Rp.
                                        {{ number_format($pengeluarans->sum('total_harga'), 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah Data</th>
                                <td><span class="badge bg-success">{{ $pengeluarans->count() }} data</span></td>
                            </tr>
                            @if(request('bulan') || request('tahun'))
                            <tr>
                                <th>Filter Aktif</th>
                                <td>
                                    @if(request('bulan') && request('tahun'))
                                        <span class="badge bg-info">{{ $bulanList[request('bulan')] }} {{ request('tahun') }}</span>
                                    @elseif(request('tahun'))
                                        <span class="badge bg-info">Tahun {{ request('tahun') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if($pengeluarans->count() > 0)
                                    @foreach ($pengeluarans as $index => $p)
                                        <tr>
                                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $p->keterangan }}</td>
                                            <td>Rp. {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                                            <td>{{ $p->jumlah }} ({{ $p->master_satuan->nama_satuan }})</td>
                                            <td>Rp. {{ number_format($p->harga_satuan * $p->jumlah, 0, ',', '.') }}</td>
                                            <td>
                                                @if($p->gambar)
                                                    <img src="{{ asset('uploads/pengeluaran/' . $p->gambar) }}" alt="Gambar" style="width: 100px; height: 100px;">
                                                @else
                                                    <img src="https://png.pngtree.com/png-vector/20190820/ourmid/pngtree-no-image-vector-illustration-isolated-png-image_1694547.jpg" alt="No Image" style="width: 100px; height: 100px;">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('pengeluaran.edit', $p->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('pengeluaran.destroy', $p->id) }}" method="POST"
                                                    style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            @if(request('bulan') || request('tahun'))
                                                Tidak ada data pengeluaran untuk filter yang dipilih.
                                            @else
                                                Tidak ada data pengeluaran.
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Tanggal</th>
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
            // Auto-submit filter form when dropdowns change
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');
            
            if (bulanSelect) {
                bulanSelect.addEventListener('change', function() {
                    if (this.value || (tahunSelect && tahunSelect.value)) {
                        this.closest('form').submit();
                    }
                });
            }
            
            if (tahunSelect) {
                tahunSelect.addEventListener('change', function() {
                    if (this.value || (bulanSelect && bulanSelect.value)) {
                        this.closest('form').submit();
                    }
                });
            }
            
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
