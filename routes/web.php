<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
    LoginController,
};

use App\Http\Controllers\admin\{
    PemasukanController,
    PengeluaranController,
    ProfilAdminController,
    MasterSatuanController,
    PembagianSaldoController,
    ManemegenAnggotaController,
    PinjamanController,
};
use App\Http\Controllers\member\{
    PembagianSaldoMemberController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/run-admin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperAdminSeeder'
    ]);

    return "AdminSeeder has been create successfully!";
});
Route::get('/', [LoginController::class, 'showLoginForm'])->name('formlogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/profil-admin', [ProfilAdminController::class, 'index'])->name('admin.profil_admin');
Route::put('/profil-admin', [ProfilAdminController::class, 'update'])->name('admin.update_profil_admin');

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('manemegen_anggota', ManemegenAnggotaController::class);
    Route::resource('pemasukan', PemasukanController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('master_satuan', MasterSatuanController::class);
    Route::get('/pembagian-saldo', [PembagianSaldoController::class, 'index'])->name('pembagian_saldo');
    Route::post('/pembagian-saldo/potong-pinjaman', [PembagianSaldoController::class, 'potongPinjaman'])->name('pembagian_saldo.potong_pinjaman');
    Route::post('/pembagian-saldo/potong-otomatis', [PembagianSaldoController::class, 'potongOtomatis'])->name('pembagian_saldo.potong_otomatis');
    Route::resource('pinjaman', PinjamanController::class);
    Route::get('pinjaman/detail/{memberId}', [PinjamanController::class, 'detail'])->name('pinjaman.detail');
    Route::post('pinjaman/angsuran/{memberId}', [PinjamanController::class, 'angsuranStore'])->name('pinjaman.angsuran.store');
});

Route::group(['middleware' => ['role:member']], function () {
    Route::get('/pembagian-saldo-member', [PembagianSaldoMemberController::class, 'index'])->name('pembagian_saldo_member');
});