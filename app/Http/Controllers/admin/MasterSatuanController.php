<?php

namespace App\Http\Controllers\admin;

use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class MasterSatuanController extends Controller
{
    public function index()
    {
        $master_satuans = MasterSatuan::all();
        return view('pageadmin.master_satuan.index', compact('master_satuans'));
    }

    public function create()
    {
        return view('pageadmin.master_satuan.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);

        MasterSatuan::create($request->all());
        Alert::toast('Master Satuan berhasil ditambahkan', 'success')->position('top-end');
        return redirect()->route('master_satuan.index');
    }

    public function edit($id)
    {
        $satuan = MasterSatuan::findOrFail($id);
        return view('pageadmin.master_satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);

        $master_satuan = MasterSatuan::findOrFail($id);
        $master_satuan->update($request->all());
        Alert::toast('Master Satuan berhasil diubah', 'success')->position('top-end');
        return redirect()->route('master_satuan.index');
    }

    public function destroy($id)
    {
        $master_satuan = MasterSatuan::findOrFail($id);
        $master_satuan->delete();
        Alert::toast('Master Satuan berhasil dihapus', 'success')->position('top-end');
        return redirect()->route('master_satuan.index');
    }
}

