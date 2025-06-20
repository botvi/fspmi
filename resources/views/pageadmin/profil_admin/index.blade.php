@extends('template-admin.layout')

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Profil Admin</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profil Admin</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="container">
                <div class="main-body">
                    <div class="row">
                        <!-- Profil Card -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="{{ $data->profil ? asset('profil/' . $data->profil) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png' }}"
                                            alt="Admin" class="rounded p-1" width="110">
                                        <div class="mt-3">
                                            <h4>{{ $data->nama }}</h4>
                                        </div>
                                    </div>

                                    <hr class="my-4" />

                                    <ul class="list-group list-group-flush">
                                      
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">
                                                <img src="{{ asset('env/svg') }}/username.svg" width="24" height="24"
                                                    class="me-2">Username
                                            </h6>
                                            <span class="text-secondary">{{ $data->username }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">
                                                <img src="{{ asset('env/svg') }}/whatsapp.svg" width="24" height="24"
                                                    class="me-2">No WhatsApp
                                            </h6>
                                            <span class="text-secondary">{{ $data->no_wa }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Edit Profil -->
                        <div class="col-lg-8">
                            <div class="card">
                                <form action="{{ route('admin.update_profil_admin') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <!-- Foto Profil -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Foto Profil</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="file" name="profil" class="form-control" />
                                            </div>
                                        </div>


                                      

                                        <!-- Nama Penanggung Jawab -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Nama</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="nama" class="form-control"
                                                    value="{{ $data->nama }}" />
                                                <small class="text-danger">
                                                    @foreach ($errors->get('nama') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Alamat -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Alamat</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <textarea name="alamat" class="form-control">{{ $data->alamat }}</textarea>
                                                <small class="text-danger">
                                                    @foreach ($errors->get('alamat') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>
                                       
                                        <!-- No WhatsApp -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">No WhatsApp</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="no_wa" class="form-control"
                                                    value="{{ $data->no_wa }}" />
                                                <small class="text-danger">
                                                    @foreach ($errors->get('no_wa') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>

                                        <hr class="my-4" />

                                        <!-- Username -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Username</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="username" class="form-control"
                                                    value="{{ $data->username }}" />
                                                <small class="text-danger">
                                                    @foreach ($errors->get('username') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Password -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Password</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Kosongkan jika tidak ingin mengubah" />
                                                <small class="text-danger">
                                                    @foreach ($errors->get('password') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Konfirmasi Password -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Konfirmasi Password</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    placeholder="Kosongkan jika tidak ingin mengubah" />
                                                <small class="text-danger">
                                                    @foreach ($errors->get('password_confirmation') as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-9 text-secondary">
                                                <button type="submit" class="btn btn-primary px-4">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end wrapper-->
@endsection
