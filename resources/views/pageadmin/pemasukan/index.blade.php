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
                                <td><span class="badge bg-success" style="font-size: 16px; font-weight: bold;">Rp.
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
                    <div class="d-flex gap-2 mb-3">
                        <a href="{{ route('pemasukan.create') }}" class="btn btn-primary">Tambah Data</a>
                        <button type="button" class="btn btn-success" onclick="printTable()">
                            <i class="bx bx-printer me-1"></i>Print Tabel
                        </button>
                    </div>
                    <div class="table-responsive" id="printableTable">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
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
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') }}</td>
                                        <td>{{ $p->keterangan }}</td>
                                        <td>Rp. {{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                                        <td>{{ $p->jumlah }} ({{ $p->master_satuan->nama_satuan ?? 'Tidak Ada Satuan' }})</td>
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
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
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

        // Function untuk print tabel
        function printTable() {
            // Buat window baru untuk print
            let printWindow = window.open('', '_blank');
            
            // Ambil tabel asli
            let originalTable = document.getElementById('example2');
            
            // Buat tabel baru untuk print (tanpa kolom Aksi)
            let printTable = originalTable.cloneNode(true);
            
            // Hapus kolom Aksi dari header
            let headerRow = printTable.querySelector('thead tr');
            let headerCells = headerRow.querySelectorAll('th');
            headerCells[5].remove(); // Hapus kolom Aksi (index 5)
            
            // Hapus kolom Aksi dari setiap baris data
            let dataRows = printTable.querySelectorAll('tbody tr');
            dataRows.forEach(row => {
                let cells = row.querySelectorAll('td');
                cells[5].remove(); // Hapus kolom Aksi (index 5)
            });
            
            // Hapus kolom Aksi dari footer
            let footerRow = printTable.querySelector('tfoot tr');
            let footerCells = footerRow.querySelectorAll('th');
            footerCells[5].remove(); // Hapus kolom Aksi (index 5)
            
            // Buat HTML untuk print
            let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Data Pemasukan - Print</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                            font-size: 12px;
                        }
                        .print-header {
                            text-align: center;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #333;
                            padding-bottom: 10px;
                        }
                        .print-header h2 {
                            margin: 0;
                            color: #333;
                        }
                        .print-header p {
                            margin: 5px 0;
                            color: #666;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                            vertical-align: top;
                        }
                        th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }
                        .print-footer {
                            margin-top: 30px;
                            text-align: right;
                            font-size: 10px;
                            color: #666;
                        }
                        img {
                            max-width: 80px;
                            max-height: 80px;
                            object-fit: cover;
                        }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2>DATA PEMASUKAN</h2>
                        <p>Tanggal Print: ${new Date().toLocaleDateString('id-ID')}</p>
                        <p>Waktu Print: ${new Date().toLocaleTimeString('id-ID')}</p>
                    </div>
                    
                    <div class="table-responsive">
                        ${printTable.outerHTML}
                    </div>
                    
                    <div class="print-footer">
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                    </div>
                </body>
                </html>
            `;
            
            // Tulis konten ke window baru
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Tunggu sebentar lalu print
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    </script>
@endsection
