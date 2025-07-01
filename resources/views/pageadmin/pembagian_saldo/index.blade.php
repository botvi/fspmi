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
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-uppercase">Data Pembagian Saldo</h6>
                <button type="button" class="btn btn-secondary" onclick="location.reload()">
                    <i class="bx bx-refresh"></i> Refresh
                </button>
            </div>
            <hr />
          
            <div class="card">
                <div class="card-body">
                    <!-- Alert Informasi Potong Otomatis -->
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Fitur Potong Otomatis:</strong> Klik tombol "Potong Otomatis & Print" untuk otomatis memotong pinjaman semua member sesuai pembagian saldo dan langsung print hasilnya. Sistem akan menggunakan tanggal hari ini sebagai tanggal angsuran dengan metode FIFO (pinjaman tertua terlebih dahulu).
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Filter Form -->
                    <form action="{{ route('pembagian_saldo') }}" method="GET" class="mb-4">
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
                        <div>
                            <button type="button" class="btn btn-success me-2" onclick="printRingkasanSaldo()">
                                <i class="bx bx-printer"></i> Print Ringkasan
                            </button>
                            <button type="button" class="btn btn-info" onclick="printTotalAkhir({{ $pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga') }}, {{ $member->count() }}, {{ $member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0 }}, {{ $member->sum('total_pinjaman') }}, {{ $member->sum('total_angsuran') }}, {{ $member->sum('sisa_pinjaman') }}, {{ ($member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0) * $member->count() - $member->sum('sisa_pinjaman') }})">
                                <i class="bx bx-printer"></i> Print Total Akhir
                            </button>
                        </div>
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
                        <div class="alert alert-info">
                            <strong>Periode:</strong> {{ $bulanList[$bulan] }} {{ $tahun }} | 
                            <strong>Total Member:</strong> {{ $member->count() }} orang | 
                            <strong>Total Saldo:</strong> Rp {{ number_format($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga'), 0, ',', '.') }} | 
                            <strong>Pembagian per Member:</strong> Rp {{ number_format($member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0, 0, ',', '.') }} | 
                            <strong>Total Pinjaman:</strong> Rp {{ number_format($member->sum('total_pinjaman'), 0, ',', '.') }} | 
                            <strong>Total Sisa Pinjaman:</strong> Rp {{ number_format($member->sum('sisa_pinjaman'), 0, ',', '.') }} | 
                            <strong>Total Saldo Akhir:</strong> Rp {{ number_format(($member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0) * $member->count() - $member->sum('sisa_pinjaman'), 0, ',', '.') }}
                        </div>
                        
                        <div class="alert alert-success">
                            <strong>Potong Otomatis:</strong> Jika Anda klik "Potong Otomatis & Print", sistem akan otomatis memotong pinjaman sebesar <strong>Rp {{ number_format(min($member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0, $member->sum('sisa_pinjaman')), 0, ',', '.') }}</strong> dari total sisa pinjaman <strong>Rp {{ number_format($member->sum('sisa_pinjaman'), 0, ',', '.') }}</strong> dan menyimpannya sebagai angsuran dengan tanggal hari ini.
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Member dan Pembagian Saldo</h5>
                            <div>
                                <button type="button" class="btn btn-danger me-2" onclick="potongOtomatis()">
                                    <i class="bx bx-cut"></i> Potong Otomatis & Print
                                </button>

                            </div>
                        </div>
                        
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
                                        <th>Sisa Pinjaman</th>
                                        <th>Saldo Akhir</th>
                                        <th>Aksi</th>
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
                                            <td class="text-end sisa-pinjaman" data-member-id="{{ $m->id }}">
                                                <strong>Rp {{ number_format($m->sisa_pinjaman, 0, ',', '.') }}</strong>
                                            </td>
                                            <td class="text-end saldo-akhir">
                                                <strong>Rp {{ number_format($pembagianPerMember - $m->sisa_pinjaman, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if($m->sisa_pinjaman > 0)
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                    @else
                                                        <span class="badge bg-success">Lunas</span>
                                                    @endif
                                                    <button type="button" class="btn btn-info btn-sm" onclick="printMemberDetail({{ $m->id }}, '{{ $m->nama }}', {{ $pembagianPerMember }}, {{ $m->total_pinjaman }}, {{ $m->total_angsuran }}, {{ $m->sisa_pinjaman }}, {{ $pembagianPerMember - $m->sisa_pinjaman }})">
                                                        <i class="bx bx-printer"></i> Print
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Tidak ada data member</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-info">
                                    @php
                                        $totalPinjaman = $member->sum('total_pinjaman');
                                        $totalAngsuran = $member->sum('total_angsuran');
                                        $totalSisaPinjaman = $member->sum('sisa_pinjaman');
                                        $totalSaldoAkhir = ($pembagianPerMember * $jumlahMember) - $totalSisaPinjaman;
                                    @endphp

                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Member:</strong></td>
                                        <td class="text-end"><strong>{{ $jumlahMember }} orang</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($totalSisaPinjaman, 0, ',', '.') }}</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($totalSaldoAkhir, 0, ',', '.') }}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Saldo:</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</strong></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="5" class="text-end"><strong>Pembagian per Member:</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($pembagianPerMember, 0, ',', '.') }}</strong></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Potong Pinjaman -->
    <div class="modal fade" id="modalPotongPinjaman" tabindex="-1" aria-labelledby="modalPotongPinjamanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPotongPinjamanLabel">Potong Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPotongPinjaman" action="{{ route('pembagian_saldo.potong_pinjaman') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle"></i>
                            <strong>Informasi:</strong> Potongan pinjaman akan otomatis disimpan sebagai angsuran dengan sistem FIFO (First In First Out). Potongan akan diterapkan pada pinjaman tertua terlebih dahulu. Tanggal angsuran akan menggunakan tanggal yang dipilih di form ini. Setelah potongan berhasil, halaman akan otomatis refresh untuk menampilkan data terbaru.
                        </div>
                        <input type="hidden" id="member_id" name="member_id">
                        <div class="mb-3">
                            <label for="nama_member" class="form-label">Nama Member</label>
                            <input type="text" class="form-control" id="nama_member" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="sisa_pinjaman" class="form-label">Sisa Pinjaman</label>
                            <input type="text" class="form-control" id="sisa_pinjaman" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_potongan" class="form-label">Jumlah Potongan</label>
                            <input type="number" class="form-control" id="jumlah_potongan" name="jumlah_potongan" required min="1" step="1000" placeholder="Masukkan jumlah potongan">
                            <small class="form-text text-muted">Maksimal: <span id="max_potongan">Rp 0</span></small>
                            <div class="mt-2">
                                <small class="text-info">Sisa setelah potongan: <span id="sisa_setelah_potongan">Rp 0</span></small>
                                <div id="lunas_info" class="mt-1" style="display: none;">
                                    <small class="text-success"><strong>Pinjaman akan lunas!</strong></small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_potongan" class="form-label">Tanggal Potongan</label>
                            <input type="date" class="form-control" id="tanggal_potongan" name="tanggal_potongan" required value="{{ date('Y-m-d') }}">
                            <small class="form-text text-muted">Tanggal ini akan digunakan sebagai tanggal angsuran dalam sistem</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Potong Pinjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gap-1 {
            gap: 0.25rem;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
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

            // Tampilkan pesan sukses jika ada
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            // Tampilkan pesan error jika ada
            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            // Jika ada pesan sukses, refresh halaman setelah 3 detik
            @if(session('success'))
                setTimeout(function() {
                    location.reload();
                }, 3000);
            @endif
        });

        function hitungUlangSaldo() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Perhitungan saldo telah diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function potongOtomatis() {
            // Konfirmasi sebelum melakukan potong otomatis
            Swal.fire({
                title: 'Konfirmasi Potong Otomatis',
                html: `
                    <p><strong>Perhatian!</strong> Aksi ini akan:</p>
                    <ul style="text-align: left;">
                        <li>Otomatis memotong pinjaman semua member sesuai pembagian saldo</li>
                        <li>Menyimpan potongan sebagai angsuran dengan tanggal hari ini</li>
                        <li>Menggunakan sistem FIFO (pinjaman tertua terlebih dahulu)</li>
                        <li>Langsung membuka print hasil pembagian saldo</li>
                    </ul>
                    <p><strong>Total Member:</strong> {{ $member->count() }} orang</p>
                    <p><strong>Pembagian per Member:</strong> Rp {{ number_format($member->count() > 0 ? ($pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga')) / $member->count() : 0, 0, ',', '.') }}</p>
                    <p><strong>Total yang akan dipotong:</strong> Rp {{ number_format($member->sum('sisa_pinjaman'), 0, ',', '.') }}</p>
                    <p class="text-warning"><strong>Apakah Anda yakin ingin melanjutkan?</strong></p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Potong Otomatis',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang memotong pinjaman dan menyimpan angsuran...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim request AJAX untuk potong otomatis
                    $.ajax({
                        url: '{{ route("pembagian_saldo.potong_otomatis") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            bulan: '{{ $bulan }}',
                            tahun: '{{ $tahun }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Langsung print hasil
                                    printDaftarMember();
                                    
                                    // Refresh halaman setelah 2 detik
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses potong otomatis',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        function showPotongPinjaman(memberId, namaMember, sisaPinjaman) {
            $('#member_id').val(memberId);
            $('#nama_member').val(namaMember);
            $('#sisa_pinjaman').val('Rp ' + sisaPinjaman.toLocaleString('id-ID'));
            $('#jumlah_potongan').attr('max', sisaPinjaman);
            $('#jumlah_potongan').val(''); // Reset input
            $('#max_potongan').text('Rp ' + sisaPinjaman.toLocaleString('id-ID'));
            $('#sisa_setelah_potongan').text('Rp ' + sisaPinjaman.toLocaleString('id-ID'));
            $('#lunas_info').hide();
            
            // Reset validasi
            $('#jumlah_potongan').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            $('#modalPotongPinjaman').modal('show');
        }

        // Validasi form potong pinjaman
        $('#formPotongPinjaman').on('submit', function(e) {
            e.preventDefault();
            
            const jumlahPotongan = parseFloat($('#jumlah_potongan').val()) || 0;
            const sisaPinjaman = parseFloat($('#sisa_pinjaman').val().replace(/[^\d]/g, ''));
            const namaMember = $('#nama_member').val();
            
            // Validasi input
            if (jumlahPotongan <= 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Jumlah potongan harus lebih dari 0',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            
            if (jumlahPotongan > sisaPinjaman) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Jumlah potongan tidak boleh melebihi sisa pinjaman',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Konfirmasi sebelum potong pinjaman
            Swal.fire({
                title: 'Konfirmasi Potong Pinjaman',
                html: `
                    <p>Apakah Anda yakin ingin memotong pinjaman untuk:</p>
                    <p><strong>${namaMember}</strong></p>
                    <p>Jumlah: <strong>Rp ${jumlahPotongan.toLocaleString('id-ID')}</strong></p>
                    <p>Tanggal: <strong>${$('#tanggal_potongan').val()}</strong></p>
                    <p>Sisa setelah potongan: <strong>Rp ${(sisaPinjaman - jumlahPotongan).toLocaleString('id-ID')}</strong></p>
                    ${(sisaPinjaman - jumlahPotongan) === 0 ? '<p><small class="text-success"><strong>Pinjaman akan lunas!</strong></small></p>' : ''}
                    <p><small class="text-info">Potongan ini akan otomatis disimpan sebagai angsuran dengan sistem FIFO (pinjaman tertua terlebih dahulu). Tanggal angsuran: ${$('#tanggal_potongan').val()}</small></p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Potong Pinjaman',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    this.submit();
                }
            });
        });

        // Validasi input jumlah potongan
        $('#jumlah_potongan').on('input', function() {
            const input = $(this);
            const jumlahPotongan = parseFloat(input.val()) || 0;
            const sisaPinjaman = parseFloat($('#sisa_pinjaman').val().replace(/[^\d]/g, ''));
            const sisaSetelahPotongan = sisaPinjaman - jumlahPotongan;
            
            // Update sisa setelah potongan
            $('#sisa_setelah_potongan').text('Rp ' + sisaSetelahPotongan.toLocaleString('id-ID'));
            
            // Reset validasi
            input.removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            // Validasi
            if (input.val() === '') {
                $('#sisa_setelah_potongan').text('Rp ' + sisaPinjaman.toLocaleString('id-ID'));
                $('#lunas_info').hide();
                return;
            }
            
            if (jumlahPotongan <= 0) {
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">Jumlah potongan harus lebih dari 0</div>');
                $('#sisa_setelah_potongan').addClass('text-danger').removeClass('text-info');
                $('#lunas_info').hide();
            } else if (jumlahPotongan > sisaPinjaman) {
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">Jumlah potongan tidak boleh melebihi sisa pinjaman</div>');
                $('#sisa_setelah_potongan').addClass('text-danger').removeClass('text-info');
                $('#lunas_info').hide();
            } else {
                input.removeClass('is-invalid');
                $('#sisa_setelah_potongan').removeClass('text-danger').addClass('text-info');
                
                // Tampilkan/sembunyikan informasi lunas
                if (sisaSetelahPotongan === 0) {
                    $('#lunas_info').show();
                } else {
                    $('#lunas_info').hide();
                }
            }
        });

        // Reset modal saat ditutup
        $('#modalPotongPinjaman').on('hidden.bs.modal', function () {
            $('#formPotongPinjaman')[0].reset();
            $('#jumlah_potongan').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#sisa_setelah_potongan').removeClass('text-danger').addClass('text-info');
            $('#lunas_info').hide();
        });

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

        function printTotalAkhir(totalSaldo, jumlahMember, pembagianPerMember, totalPinjaman, totalAngsuran, totalSisaPinjaman, totalSaldoAkhir) {
            // Membuat konten untuk print
            var printContent = `
                <html>
                <head>
                    <title>Total Akhir Pembagian Saldo - {{ $bulanList[$bulan] }} {{ $tahun }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .header h1 { color: #333; margin-bottom: 10px; }
                        .header p { color: #666; margin: 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
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
                        <h1>TOTAL AKHIR PEMBAGIAN SALDO</h1>
                        <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center; background-color: #28a745; color: white;">Ringkasan Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="50%">Total Saldo</td>
                                <td class="text-end">Rp ${totalSaldo.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Member</td>
                                <td class="text-end">${jumlahMember} orang</td>
                            </tr>
                            <tr>
                                <td>Pembagian per Member</td>
                                <td class="text-end">Rp ${pembagianPerMember.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Total Pinjaman</td>
                                <td class="text-end">Rp ${totalPinjaman.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Total Angsuran</td>
                                <td class="text-end">Rp ${totalAngsuran.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Total Sisa Pinjaman</td>
                                <td class="text-end">Rp ${totalSisaPinjaman.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>TOTAL SALDO AKHIR</strong></td>
                                <td class="text-end"><strong>Rp ${totalSaldoAkhir.toLocaleString('id-ID')}</strong></td>
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

        function printMemberDetail(memberId, namaMember, pembagianSaldo, totalPinjaman, totalAngsuran, sisaPinjaman, saldoAkhir) {
            // Membuat konten untuk print
            var printContent = `
                <html>
                <head>
                    <title>Detail Member - ${namaMember}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .header h1 { color: #333; margin-bottom: 10px; }
                        .header p { color: #666; margin: 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
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
                        <h1>DETAIL MEMBER</h1>
                        <p>Nama: ${namaMember}</p>
                        <p>Periode: {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center; background-color: #17a2b8; color: white;">Detail Pembagian Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="50%">Pembagian Saldo</td>
                                <td class="text-end">Rp ${pembagianSaldo.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Total Pinjaman</td>
                                <td class="text-end">Rp ${totalPinjaman.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Total Angsuran</td>
                                <td class="text-end">Rp ${totalAngsuran.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td>Sisa Pinjaman</td>
                                <td class="text-end">Rp ${sisaPinjaman.toLocaleString('id-ID')}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Saldo Akhir</strong></td>
                                <td class="text-end"><strong>Rp ${saldoAkhir.toLocaleString('id-ID')}</strong></td>
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
                        <p><strong>Status:</strong> Setelah Potong Otomatis Pinjaman</p>
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
                                <th>Sisa Pinjaman</th>
                                <th>Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSaldo = $pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga');
                                $jumlahMember = $member->count();
                                $pembagianPerMember = $jumlahMember > 0 ? $totalSaldo / $jumlahMember : 0;
                                $totalSisaPinjaman = $member->sum('sisa_pinjaman');
                                $totalSaldoAkhir = ($pembagianPerMember * $jumlahMember) - $totalSisaPinjaman;
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
                                        <strong>Rp {{ number_format($m->sisa_pinjaman, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($pembagianPerMember - $m->sisa_pinjaman, 0, ',', '.') }}</strong>
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
                                <td class="text-end"><strong>Rp {{ number_format($totalSisaPinjaman, 0, ',', '.') }}</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($totalSaldoAkhir, 0, ',', '.') }}</strong></td>
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
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
@endsection
