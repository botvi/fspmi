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
                            <li class="breadcrumb-item active" aria-current="page">Pinjaman</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Pinjaman Member</h6>
            <hr />
          
            <div class="card">
                <div class="card-body">
                        <a href="{{ route('pinjaman.create') }}" class="btn btn-primary mb-3">Tambah Pinjaman</a>
                        <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Member</th>
                                    <th>Total Pinjaman</th>
                                    <th>Total Angsuran</th>
                                    <th>Sisa Pinjaman</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{ $member->nama }}</td>
                                        <td>Rp. {{ number_format($member->total_pinjaman, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($member->total_angsuran, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $member->sisa_pinjaman > 0 ? 'bg-danger' : 'bg-success' }}" style="font-size: 14px; font-weight: bold; color: rgb(0, 0, 0);">
                                                Rp. {{ number_format($member->sisa_pinjaman, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>{{ $member->pinjaman->count() }} transaksi</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#angsuranModal{{ $member->id }}"
                                                    {{ $member->sisa_pinjaman <= 0 ? 'disabled' : '' }}>
                                                Angsuran
                                            </button>
                                            <a href="{{ route('pinjaman.detail', $member->id) }}"
                                                class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama Member</th>
                                    <th>Total Pinjaman</th>
                                    <th>Total Angsuran</th>
                                    <th>Sisa Pinjaman</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Angsuran untuk setiap member -->
    @foreach ($members as $member)
    @if($member->sisa_pinjaman > 0)
    <div class="modal fade" id="angsuranModal{{ $member->id }}" tabindex="-1" aria-labelledby="angsuranModalLabel{{ $member->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="angsuranModalLabel{{ $member->id }}">Input Angsuran - {{ $member->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pinjaman.angsuran.store', $member->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Member</label>
                            <input type="text" class="form-control" value="{{ $member->nama }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sisa Pinjaman</label>
                            <input type="text" class="form-control" value="Rp. {{ number_format($member->sisa_pinjaman, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_angsuran{{ $member->id }}" class="form-label">Jumlah Angsuran</label>
                            <input type="number" class="form-control" id="jumlah_angsuran{{ $member->id }}" name="jumlah_angsuran" required min="1" max="{{ $member->sisa_pinjaman }}">
                            <div class="form-text">Maksimal angsuran: Rp. {{ number_format($member->sisa_pinjaman, 0, ',', '.') }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_angsuran{{ $member->id }}" class="form-label">Tanggal Angsuran</label>
                            <input type="date" class="form-control" id="tanggal_angsuran{{ $member->id }}" name="tanggal_angsuran" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Angsuran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
