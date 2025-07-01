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
                            <li class="breadcrumb-item active" aria-current="page">Manajemen Anggota</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Manajemen Anggota</h6>
            <hr />
          
            <div class="card">
                <div class="card-body">
                        <a href="{{ route('manemegen_anggota.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                        <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Anggota</th>
                                    <th>Jabatan</th>
                                    <th>Nama Anggota</th>
                                    <th>No HP Anggota</th>
                                    <th>Alamat Anggota</th>
                                    <th>Foto Anggota</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anggotas as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $p->no_anggota }}</td>
                                        <td>{{ $p->jabatan }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->no_wa }}</td>
                                        <td>{{ $p->alamat }}</td>
                                        <td>
                                            @if($p->profil)
                                                <img src="{{ asset('profil/' . $p->profil) }}" alt="Foto Anggota" style="width: 100px; height: 100px;">
                                            @else
                                                <img src="https://png.pngtree.com/png-vector/20190820/ourmid/pngtree-no-image-vector-illustration-isolated-png-image_1694547.jpg" alt="No Image" style="width: 100px; height: 100px;">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manemegen_anggota.edit', $p->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('manemegen_anggota.destroy', $p->id) }}" method="POST"
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
                                    <th>Nomor Anggota</th>
                                    <th>Jabatan</th>
                                    <th>Nama Anggota</th>
                                    <th>No HP Anggota</th>
                                    <th>Alamat Anggota</th>
                                    <th>Foto Anggota</th>
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
