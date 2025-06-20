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
                            <li class="breadcrumb-item active" aria-current="page">Pembagian Saldo</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Pembagian Saldo</h6>
            <hr />

      
          
            <div class="card">
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('pembagian_saldo_member') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bulan">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control">
                                        @foreach($bulanList as $key => $value)
                                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahun }}" min="2000" max="2100">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Ringkasan Saldo -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Ringkasan Saldo</h5>
                    
                    </div>
                    
                    <div id="ringkasan-saldo" class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-info">
                                <tr>
                                    <th colspan="2" class="text-center">Ringkasan Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="50%">Total Pemasukan</td>
                                    <td>Rp {{ number_format($pemasukans->sum('total_harga')) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Pengeluaran</td>
                                    <td>Rp {{ number_format($pengeluarans->sum('total_harga')) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>Saldo bulan {{ $bulanList[$bulan] }} {{ $tahun }}</strong></td>
                                    <td><strong>Rp {{ number_format($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabel Daftar Member dan Pembagian Saldo -->
                    <div class="mt-4">
                       
                        
                        <div id="daftar-member" class="table-responsive">
                            <table class="table table-bordered table-striped" id="table-member">
                                <thead class="table-success">
                                    <tr>
                                        <th>No</th>
                                        <th>No Anggota</th>
                                        <th>Nama Member</th>
                                        <th>No WhatsApp</th>
                                        <th>Alamat</th>
                                        <th>Pembagian Saldo</th>
                                
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSaldo = $pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga');
                                        $jumlahMember = $member->count();
                                        $pembagianPerMember = $jumlahMember > 0 ? $totalSaldo / $jumlahMember : 0;
                                    @endphp
                                    
                                    @forelse($member as $index => $m)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $m->no_anggota }}</td>
                                            <td>{{ $m->nama }}</td>
                                            <td>{{ $m->no_wa }}</td>
                                            <td>{{ $m->alamat }}</td>
                                            <td class="text-end pembagian-saldo">
                                                <strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong>
                                            </td>
                                          
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data member</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-info">
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Member:</strong></td>
                                        <td class="text-end"><strong>{{ $jumlahMember }} orang</strong></td>
                                      
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Saldo:</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="5" class="text-end"><strong>Pembagian per Member:</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    

                
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#example2').DataTable();
            $('#table-member').DataTable({
                "pageLength": 25,
                "order": [[ 0, "asc" ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });

        function hitungSaldoAkhir(input) {
            const row = $(input).closest('tr');
            const pembagianSaldoText = row.find('.pembagian-saldo strong').text();
            const pembagianSaldo = parseFloat(pembagianSaldoText.replace(/[^\d]/g, ''));
            const hutang = parseFloat(input.value) || 0;
            const saldoAkhir = pembagianSaldo - hutang;
            
            row.find('.saldo-akhir strong').text('Rp ' + saldoAkhir.toLocaleString('id-ID'));
            
            hitungTotal();
        }

        function hitungTotal() {
            let totalHutang = 0;
            let totalSaldoAkhir = 0;
            
            $('.hutang-input').each(function() {
                const hutang = parseFloat($(this).val()) || 0;
                totalHutang += hutang;
            });
            
            $('.saldo-akhir strong').each(function() {
                const saldoText = $(this).text();
                const saldo = parseFloat(saldoText.replace(/[^\d]/g, '')) || 0;
                totalSaldoAkhir += saldo;
            });
            
            $('#total-hutang').text('Rp ' + totalHutang.toLocaleString('id-ID'));
            $('#total-saldo-akhir').text('Rp ' + totalSaldoAkhir.toLocaleString('id-ID'));
        }

        function hitungUlangSaldo() {
            $('.hutang-input').each(function() {
                hitungSaldoAkhir(this);
            });
            
            Swal.fire({
                title: 'Berhasil!',
                text: 'Perhitungan saldo telah diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function printRingkasanSaldo() {
            // Membuat konten untuk print
            var printContent = `
                <html>
                <head>
                    <title>Ringkasan Saldo - {{ $bulanList[$bulan] }} {{ $tahun }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .header h1 { color: #333; margin-bottom: 10px; }
                        .header p { color: #666; margin: 0; }
                        .user-info { background-color: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        .total-row { background-color: #e3f2fd; font-weight: bold; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>RINGKASAN SALDO</h1>
                        <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>

                    
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center; background-color: #17a2b8; color: white;">Ringkasan Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="50%">Total Pemasukan</td>
                                <td>Rp {{ number_format($pemasukans->sum('total_harga')) }}</td>
                            </tr>
                            <tr>
                                <td>Total Pengeluaran</td>
                                <td>Rp {{ number_format($pengeluarans->sum('total_harga')) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Saldo bulan {{ $bulanList[$bulan] }} {{ $tahun }}</strong></td>
                                <td><strong>Rp {{ number_format($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="footer">
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                        <p>Sistem Informasi FSPMI</p>
                    </div>
                </body>
                </html>
            `;

            // Membuat window baru untuk print
            var printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Menunggu konten dimuat sebelum print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }

        function printDaftarMember() {
            // Mengumpulkan data hutang untuk print
            let hutangData = [];
            $('.hutang-input').each(function() {
                const memberId = $(this).data('member-id');
                const hutang = parseFloat($(this).val()) || 0;
                hutangData.push({ memberId: memberId, hutang: hutang });
            });

            // Membuat konten untuk print
            var printContent = `
                <html>
                <head>
                    <title>Daftar Member dan Pembagian Saldo - {{ $bulanList[$bulan] }} {{ $tahun }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .header h1 { color: #333; margin-bottom: 10px; }
                        .header p { color: #666; margin: 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #28a745; color: white; font-weight: bold; }
                        .total-row { background-color: #e3f2fd; font-weight: bold; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                        .text-end { text-align: right; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>DAFTAR MEMBER DAN PEMBAGIAN SALDO</h1>
                        <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Anggota</th>
                                <th>Nama Member</th>
                                <th>No WhatsApp</th>
                                <th>Alamat</th>
                                <th>Pembagian Saldo</th>
                                <th>Hutang</th>
                                <th>Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSaldo = $pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga');
                                $jumlahMember = $member->count();
                                $pembagianPerMember = $jumlahMember > 0 ? $totalSaldo / $jumlahMember : 0;
                            @endphp
                            
                            @forelse($member as $index => $m)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $m->no_anggota }}</td>
                                    <td>{{ $m->nama }}</td>
                                    <td>{{ $m->no_wa }}</td>
                                    <td>{{ $m->alamat }}</td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span id="hutang-print-{{ $m->id }}">Rp 0</span>
                                    </td>
                                    <td class="text-end">
                                        <span id="saldo-akhir-print-{{ $m->id }}">
                                            <strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data member</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #17a2b8; color: white;">
                                <td colspan="5" class="text-end"><strong>Total Member:</strong></td>
                                <td class="text-end"><strong>{{ $jumlahMember }} orang</strong></td>
                                <td class="text-end"><strong id="total-hutang-print">Rp 0</strong></td>
                                <td class="text-end"><strong id="total-saldo-akhir-print">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr style="background-color: #17a2b8; color: white;">
                                <td colspan="5" class="text-end"><strong>Total Saldo:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr style="background-color: #ffc107;">
                                <td colspan="5" class="text-end"><strong>Pembagian per Member:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="footer">
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                        <p>Sistem Informasi FSPMI</p>
                    </div>
                </body>
                </html>
            `;

            // Membuat window baru untuk print
            var printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Menunggu konten dimuat sebelum print
            printWindow.onload = function() {
                // Update data hutang dan saldo akhir di window print
                $('.hutang-input').each(function() {
                    const memberId = $(this).data('member-id');
                    const hutang = parseFloat($(this).val()) || 0;
                    const pembagianSaldo = {{ $pembagianPerMember }};
                    const saldoAkhir = pembagianSaldo - hutang;
                    
                    printWindow.document.getElementById('hutang-print-' + memberId).textContent = 'Rp ' + hutang.toLocaleString('id-ID');
                    printWindow.document.getElementById('saldo-akhir-print-' + memberId).innerHTML = '<strong>Rp ' + saldoAkhir.toLocaleString('id-ID') + '</strong>';
                });
                
                // Update total
                let totalHutang = 0;
                let totalSaldoAkhir = 0;
                
                $('.hutang-input').each(function() {
                    const hutang = parseFloat($(this).val()) || 0;
                    totalHutang += hutang;
                });
                
                $('.saldo-akhir strong').each(function() {
                    const saldoText = $(this).text();
                    const saldo = parseFloat(saldoText.replace(/[^\d]/g, '')) || 0;
                    totalSaldoAkhir += saldo;
                });
                
                printWindow.document.getElementById('total-hutang-print').textContent = 'Rp ' + totalHutang.toLocaleString('id-ID');
                printWindow.document.getElementById('total-saldo-akhir-print').textContent = 'Rp ' + totalSaldoAkhir.toLocaleString('id-ID');
                
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endsection
