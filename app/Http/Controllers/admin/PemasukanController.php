<?php

namespace App\Http\Controllers\admin;

use App\Models\Pemasukan;
use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasukan::orderBy('created_at', 'desc')->with('master_satuan');
        
        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun)
                  ->whereMonth('created_at', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }
        
        $pemasukans = $query->get();
        
        // Data untuk dropdown filter
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tahunList = range(date('Y') - 5, date('Y') + 1);
        
        return view('pageadmin.pemasukan.index', compact('pemasukans', 'bulanList', 'tahunList'));
    }

    public function create()
    {
        $master_satuans = MasterSatuan::all();
        return view('pageadmin.pemasukan.create', compact('master_satuans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal_masuk' => 'required|date',
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
            $pemasukan = Pemasukan::create([
                'user_id' => Auth::user()->id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->jumlah,
                'master_satuan_id' => $request->master_satuan_id,
                'total_harga' => $request->total_harga,
            ]);

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/pemasukan', $namaFile);
                $pemasukan->gambar = $namaFile;
                $pemasukan->save();
            }

            Alert::toast('Pemasukan berhasil ditambahkan!', 'success')->position('top-end');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $master_satuans = MasterSatuan::all();
            return view('pageadmin.pemasukan.edit', compact('pemasukan', 'master_satuans'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('pemasukan.index');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal_masuk' => 'required|date',
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
            $pemasukan = Pemasukan::findOrFail($id);
            
            // Update data dasar
            $pemasukan->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->jumlah,
                'master_satuan_id' => $request->master_satuan_id,
                'total_harga' => $request->total_harga,
            ]);

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($pemasukan->gambar && file_exists(public_path('uploads/pemasukan/' . $pemasukan->gambar))) {
                    unlink(public_path('uploads/pemasukan/' . $pemasukan->gambar));
                }

                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/pemasukan', $namaFile);
                $pemasukan->gambar = $namaFile;
                $pemasukan->save();
            }

            Alert::toast('Pemasukan berhasil diubah!', 'success')->position('top-end');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($pemasukan->gambar && file_exists(public_path('uploads/pemasukan/' . $pemasukan->gambar))) {
                unlink(public_path('uploads/pemasukan/' . $pemasukan->gambar));
            }
            
            $pemasukan->delete();
            Alert::toast('Pemasukan berhasil dihapus!', 'success')->position('top-end');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
        
        return redirect()->route('pemasukan.index');
    }
}
