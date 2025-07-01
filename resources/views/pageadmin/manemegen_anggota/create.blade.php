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
                            <li class="breadcrumb-item active" aria-current="page">Tambah Anggota</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-7 mx-auto">
                    <hr />
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-plus-circle me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Tambah Anggota</h5>
                            </div>
                            <hr>
                            <form action="{{ route('manemegen_anggota.store') }}" method="POST" class="row g-3"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6">
                                    <label for="no_anggota" class="form-label">Nomor Anggota</label>
                                    <input type="text" class="form-control uppercase" id="no_anggota" name="no_anggota" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('no_anggota') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control uppercase" id="jabatan" name="jabatan" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('jabatan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Anggota</label>
                                    <input type="text" class="form-control uppercase" id="nama" name="nama" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('nama') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_wa" class="form-label">No HP Anggota</label>
                                    <input type="text" class="form-control uppercase" id="no_wa" name="no_wa" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('no_wa') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="alamat" class="form-label">Alamat Anggota</label>
                                    <input type="text" class="form-control uppercase" id="alamat" name="alamat" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('alamat') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="profil" class="form-label">Foto Anggota</label>
                                    <input type="file" class="form-control" id="profil" name="profil" accept="image/*">
                                    <small class="text-danger">
                                        @foreach ($errors->get('profil') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <hr>
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control uppercase" id="username" name="username" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('username') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('password') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('password_confirmation') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
