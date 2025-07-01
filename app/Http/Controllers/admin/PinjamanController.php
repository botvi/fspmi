<?php

namespace App\Http\Controllers\admin;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Angsuran;

class PinjamanController extends Controller
{
    public function index()
    {
        // Ambil semua member yang memiliki pinjaman
        $members = User::where('role', 'member')
            ->whereHas('pinjaman')
            ->with(['pinjaman' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        // Hitung total dan sisa pinjaman untuk setiap member
        foreach ($members as $member) {
            $member->total_pinjaman = Pinjaman::getTotalPinjamanByMember($member->id);
            $member->total_angsuran = Pinjaman::getTotalAngsuranByMember($member->id);
            $member->sisa_pinjaman = Pinjaman::getSisaPinjamanByMember($member->id);
        }

        return view('pageadmin.pinjaman.index', compact('members'));
    }

    public function create()
    {
        $member = User::where('role', 'member')->get();
        return view('pageadmin.pinjaman.create', compact('member'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'keterangan' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:1'
        ]);

        $pinjaman = Pinjaman::create($request->all());
        Alert::success('Success', 'Pinjaman berhasil ditambahkan');
        return redirect()->route('pinjaman.index');
    }

    public function edit($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        $member = User::where('role', 'member')->get();
        return view('pageadmin.pinjaman.edit', compact('pinjaman', 'member'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'keterangan' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:1'
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        $pinjaman->update($request->all());
        Alert::success('Success', 'Pinjaman berhasil diubah');
        return redirect()->route('pinjaman.index');
    }

    public function destroy($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        $pinjaman->delete();
        Alert::success('Success', 'Pinjaman berhasil dihapus');
        return redirect()->route('pinjaman.index');
    }
   
    public function angsuranStore(Request $request, $memberId)
    {
        $request->validate([
            'jumlah_angsuran' => 'required|numeric|min:1',
            'tanggal_angsuran' => 'required|date'
        ]);

        $member = User::findOrFail($memberId);
        $sisaPinjaman = Pinjaman::getSisaPinjamanByMember($memberId);
        
        // Validasi jumlah angsuran tidak boleh melebihi sisa pinjaman
        if ($request->jumlah_angsuran > $sisaPinjaman) {
            Alert::error('Error', 'Jumlah angsuran tidak boleh melebihi sisa pinjaman (Rp. ' . number_format($sisaPinjaman, 0, ',', '.') . ')');
            return redirect()->back()->withInput();
        }

        // Ambil semua pinjaman member yang belum lunas, urutkan berdasarkan tanggal (FIFO)
        $pinjamanList = Pinjaman::where('member_id', $memberId)
            ->orderBy('created_at', 'asc')
            ->get();

        $jumlahAngsuranSisa = $request->jumlah_angsuran;

        foreach ($pinjamanList as $pinjaman) {
            if ($jumlahAngsuranSisa <= 0) break;

            // Hitung total angsuran untuk pinjaman ini
            $totalAngsuranPinjaman = $pinjaman->angsurans->sum('jumlah_angsuran');
            $sisaPinjamanItem = $pinjaman->jumlah_pinjaman - $totalAngsuranPinjaman;

            if ($sisaPinjamanItem > 0) {
                // Tentukan jumlah angsuran untuk pinjaman ini
                $angsuranUntukPinjaman = min($jumlahAngsuranSisa, $sisaPinjamanItem);

                // Buat record angsuran
                Angsuran::create([
                    'pinjaman_id' => $pinjaman->id,
                    'member_id' => $memberId,
                    'jumlah_angsuran' => $angsuranUntukPinjaman,
                    'tanggal_angsuran' => $request->tanggal_angsuran
                ]);

                $jumlahAngsuranSisa -= $angsuranUntukPinjaman;
            }
        }

        Alert::success('Success', 'Angsuran berhasil ditambahkan');
        return redirect()->route('pinjaman.index');
    }

    // Method untuk menampilkan detail pinjaman per member
    public function detail($memberId)
    {
        $member = User::findOrFail($memberId);
        $pinjaman = Pinjaman::where('member_id', $memberId)
            ->with(['angsurans' => function($query) {
                $query->orderBy('tanggal_angsuran', 'desc');
            }])
            ->get();

        $totalPinjaman = Pinjaman::getTotalPinjamanByMember($memberId);
        $totalAngsuran = Pinjaman::getTotalAngsuranByMember($memberId);
        $sisaPinjaman = Pinjaman::getSisaPinjamanByMember($memberId);

        return view('pageadmin.pinjaman.detail', compact('member', 'pinjaman', 'totalPinjaman', 'totalAngsuran', 'sisaPinjaman'));
    }
}
