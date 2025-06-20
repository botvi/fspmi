<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use App\Models\User;

class PembagianSaldoController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $pemasukans = Pemasukan::whereMonth('created_at', $bulan)
                              ->whereYear('created_at', $tahun)
                              ->get();
                              
        $pengeluarans = Pengeluaran::whereMonth('created_at', $bulan)
                                  ->whereYear('created_at', $tahun)
                                  ->get();

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $member = User::where('role', 'member')->get();

        return view('pageadmin.pembagian_saldo.index', compact('pemasukans', 'pengeluarans', 'bulanList', 'bulan', 'tahun', 'member'));
    }
}