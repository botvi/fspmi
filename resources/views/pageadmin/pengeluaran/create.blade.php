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
                            <li class="breadcrumb-item active" aria-current="page">Tambah Pengeluaran</li>
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
                                <h5 class="mb-0 text-primary">Tambah Pengeluaran</h5>
                            </div>
                            <hr>
                            <form action="{{ route('pengeluaran.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                                    <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('tanggal_keluar') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea rows="3" type="text" class="form-control" id="keterangan" name="keterangan"
                                        required></textarea>
                                    <small class="text-danger">
                                        @foreach ($errors->get('keterangan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                    <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required oninput="hitungTotal()">
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_satuan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" required oninput="hitungTotal()">
                                    <small class="text-danger">
                                        @foreach ($errors->get('jumlah') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="master_satuan_id" class="form-label text-danger">*Bisa Di Kosongkan!</label>
                                    <label for="master_satuan_id" class="form-label">Satuan</label>
                                    <select name="master_satuan_id" id="master_satuan_id" class="form-control">
                                        <option value="">Pilih Satuan</option>
                                        @foreach ($master_satuans as $ms)
                                            <option value="{{ $ms->id }}">{{ $ms->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('master_satuan_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="total_harga" class="form-label">Total Harga</label>
                                    <input type="number" class="form-control" id="total_harga" name="total_harga" readonly required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('total_harga') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                            
                                <div class="col-md-12">
                                    <label class="text-danger">* gambar tidak wajib!</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar">
                                    <small class="text-danger">
                                        @foreach ($errors->get('gambar') as $error)
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

    @section('script')
    <script>
        function hitungTotal() {
            const hargaSatuan = document.getElementById('harga_satuan').value;
            const jumlah = document.getElementById('jumlah').value;
            const totalHarga = hargaSatuan * jumlah;
            
            document.getElementById('total_harga').value = totalHarga;
        }
    </script>
    @endsection
@endsection
