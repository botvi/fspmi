<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Pinjaman;
use App\Models\Angsuran;
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

        // Ambil data pinjaman untuk setiap member
        foreach ($member as $m) {
            $m->total_pinjaman = Pinjaman::getTotalPinjamanByMember($m->id);
            $m->total_angsuran = Pinjaman::getTotalAngsuranByMember($m->id);
            $m->sisa_pinjaman = Pinjaman::getSisaPinjamanByMember($m->id);
        }

        return view('pageadmin.pembagian_saldo.index', compact('pemasukans', 'pengeluarans', 'bulanList', 'bulan', 'tahun', 'member'));
    }

    public function potongPinjaman(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'jumlah_potongan' => 'required|numeric|min:1',
            'tanggal_potongan' => 'required|date'
        ]);

        $member = User::findOrFail($request->member_id);
        $sisaPinjaman = Pinjaman::getSisaPinjamanByMember($request->member_id);
        
        // Validasi jumlah potongan tidak boleh melebihi sisa pinjaman
        if ($request->jumlah_potongan > $sisaPinjaman) {
            Alert::error('Error!', 'Jumlah potongan tidak boleh melebihi sisa pinjaman (Rp. ' . number_format($sisaPinjaman, 0, ',', '.') . ') untuk member ' . $member->nama);
            return redirect()->back()->withInput();
        }

        // Ambil semua pinjaman member yang belum lunas, urutkan berdasarkan tanggal (FIFO)
        $pinjamanList = Pinjaman::where('member_id', $request->member_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $jumlahPotonganSisa = $request->jumlah_potongan;

        foreach ($pinjamanList as $pinjaman) {
            if ($jumlahPotonganSisa <= 0) break;

            // Hitung total angsuran untuk pinjaman ini
            $totalAngsuranPinjaman = $pinjaman->angsurans->sum('jumlah_angsuran');
            $sisaPinjamanItem = $pinjaman->jumlah_pinjaman - $totalAngsuranPinjaman;

            if ($sisaPinjamanItem > 0) {
                // Tentukan jumlah angsuran untuk pinjaman ini
                $angsuranUntukPinjaman = min($jumlahPotonganSisa, $sisaPinjamanItem);

                // Buat record angsuran
                Angsuran::create([
                    'pinjaman_id' => $pinjaman->id,
                    'member_id' => $request->member_id,
                    'jumlah_angsuran' => $angsuranUntukPinjaman,
                    'tanggal_angsuran' => $request->tanggal_potongan
                ]);

                $jumlahPotonganSisa -= $angsuranUntukPinjaman;
            }
        }

        $sisaSetelahPotongan = $sisaPinjaman - $request->jumlah_potongan;
        $pesan = 'Potongan pinjaman sebesar Rp ' . number_format($request->jumlah_potongan, 0, ',', '.') . ' berhasil disimpan sebagai angsuran untuk ' . $member->nama . ' pada tanggal ' . $request->tanggal_potongan;
        
        if ($sisaSetelahPotongan > 0) {
            $pesan .= '. Sisa pinjaman: Rp ' . number_format($sisaSetelahPotongan, 0, ',', '.');
        } else {
            $pesan .= '. Pinjaman telah lunas!';
        }
        
        $pesan .= ' (Potongan diterapkan dengan sistem FIFO)';
        
        Alert::success('Berhasil!', $pesan);
        return redirect()->back();
    }

    public function potongOtomatis(Request $request)
    {
        try {
            $bulan = $request->get('bulan', Carbon::now()->month);
            $tahun = $request->get('tahun', Carbon::now()->year);
            $tanggalPotongan = Carbon::now()->format('Y-m-d');

            // Ambil data pemasukan dan pengeluaran
            $pemasukans = Pemasukan::whereMonth('created_at', $bulan)
                                  ->whereYear('created_at', $tahun)
                                  ->get();
                                  
            $pengeluarans = Pengeluaran::whereMonth('created_at', $bulan)
                                      ->whereYear('created_at', $tahun)
                                      ->get();

            // Hitung total saldo dan pembagian per member
            $totalSaldo = $pemasukans->sum('total_harga') - $pengeluarans->sum('total_harga');
            $member = User::where('role', 'member')->get();
            $jumlahMember = $member->count();
            $pembagianPerMember = $jumlahMember > 0 ? $totalSaldo / $jumlahMember : 0;

            $totalPotongan = 0;
            $memberDiproses = 0;
            $memberLunas = 0;

            foreach ($member as $m) {
                $sisaPinjaman = Pinjaman::getSisaPinjamanByMember($m->id);
                
                if ($sisaPinjaman > 0) {
                    // Tentukan jumlah potongan (minimal antara pembagian saldo atau sisa pinjaman)
                    $jumlahPotongan = min($pembagianPerMember, $sisaPinjaman);
                    
                    if ($jumlahPotongan > 0) {
                        // Ambil semua pinjaman member yang belum lunas, urutkan berdasarkan tanggal (FIFO)
                        $pinjamanList = Pinjaman::where('member_id', $m->id)
                            ->orderBy('created_at', 'asc')
                            ->get();

                        $jumlahPotonganSisa = $jumlahPotongan;

                        foreach ($pinjamanList as $pinjaman) {
                            if ($jumlahPotonganSisa <= 0) break;

                            // Hitung total angsuran untuk pinjaman ini
                            $totalAngsuranPinjaman = $pinjaman->angsurans->sum('jumlah_angsuran');
                            $sisaPinjamanItem = $pinjaman->jumlah_pinjaman - $totalAngsuranPinjaman;

                            if ($sisaPinjamanItem > 0) {
                                // Tentukan jumlah angsuran untuk pinjaman ini
                                $angsuranUntukPinjaman = min($jumlahPotonganSisa, $sisaPinjamanItem);

                                // Buat record angsuran
                                Angsuran::create([
                                    'pinjaman_id' => $pinjaman->id,
                                    'member_id' => $m->id,
                                    'jumlah_angsuran' => $angsuranUntukPinjaman,
                                    'tanggal_angsuran' => $tanggalPotongan
                                ]);

                                $jumlahPotonganSisa -= $angsuranUntukPinjaman;
                            }
                        }

                        $totalPotongan += $jumlahPotongan;
                        $memberDiproses++;

                        // Cek apakah pinjaman lunas setelah potongan
                        $sisaSetelahPotongan = $sisaPinjaman - $jumlahPotongan;
                        if ($sisaSetelahPotongan <= 0) {
                            $memberLunas++;
                        }
                    }
                }
            }

            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $pesan = "Potong otomatis berhasil! Periode: {$bulanList[$bulan]} {$tahun}. ";
            $pesan .= "Total potongan: Rp " . number_format($totalPotongan, 0, ',', '.') . ". ";
            $pesan .= "Member diproses: {$memberDiproses} orang. ";
            $pesan .= "Member lunas: {$memberLunas} orang. ";
            $pesan .= "Pembagian per member: Rp " . number_format($pembagianPerMember, 0, ',', '.') . ". ";
            $pesan .= "Tanggal potongan: {$tanggalPotongan}.";

            return response()->json([
                'success' => true,
                'message' => $pesan,
                'data' => [
                    'total_potongan' => $totalPotongan,
                    'member_diproses' => $memberDiproses,
                    'member_lunas' => $memberLunas,
                    'pembagian_per_member' => $pembagianPerMember,
                    'tanggal_potongan' => $tanggalPotongan
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}