<?php

namespace App\Http\Controllers\admin;

use App\Models\Pengeluaran;
use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::orderBy('created_at', 'desc')->with('master_satuan');
        
        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun)
                  ->whereMonth('created_at', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }
        
        $pengeluarans = $query->get();
        
        // Data untuk dropdown filter
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tahunList = range(date('Y') - 5, date('Y') + 1);

        
        return view('pageadmin.pengeluaran.index', compact('pengeluarans', 'bulanList', 'tahunList'));
    }

    public function create()
    {
        $master_satuans = MasterSatuan::all();
        return view('pageadmin.pengeluaran.create', compact('master_satuans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_keluar' => 'required|date',
                'keterangan' => 'required|string|max:255',
                'harga_satuan' => 'required|numeric|min:0',
                'jumlah' => 'required|integer|min:1',
                'master_satuan_id' => 'nullable|exists:master_satuans,id',
                'total_harga' => 'required|numeric|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $pengeluaran = Pengeluaran::create([
                'user_id' => Auth::user()->id,
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->total_harga,
                'master_satuan_id' => $request->master_satuan_id,
            ]);

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/pengeluaran', $namaFile);
                $pengeluaran->gambar = $namaFile;
                $pengeluaran->save();
            }

            Alert::toast('Pengeluaran berhasil ditambahkan!', 'success')->position('top-end');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            return view('pageadmin.pengeluaran.edit', compact('pengeluaran'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('pengeluaran.index');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal_keluar' => 'required|date',
                'keterangan' => 'required|string|max:255',
                'harga_satuan' => 'required|numeric|min:0',
                'jumlah' => 'required|integer|min:1',
                'master_satuan_id' => 'nullable|exists:master_satuans,id',
                'total_harga' => 'required|numeric|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            
            // Update data dasar
            $pengeluaran->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->total_harga,
                'master_satuan_id' => $request->master_satuan_id,
            ]);

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($pengeluaran->gambar && file_exists(public_path('uploads/pengeluaran/' . $pengeluaran->gambar))) {
                    unlink(public_path('uploads/pengeluaran/' . $pengeluaran->gambar));
                }

                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/pengeluaran', $namaFile);
                $pengeluaran->gambar = $namaFile;
                $pengeluaran->save();
            }

            Alert::toast('Pengeluaran berhasil diubah!', 'success')->position('top-end');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($pengeluaran->gambar && file_exists(public_path('uploads/pengeluaran/' . $pengeluaran->gambar))) {
                unlink(public_path('uploads/pengeluaran/' . $pengeluaran->gambar));
            }
            
            $pengeluaran->delete();
            Alert::toast('Pengeluaran berhasil dihapus!', 'success')->position('top-end');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
        
        return redirect()->route('pengeluaran.index');
    }
}
