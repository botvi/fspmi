<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class ManemegenAnggotaController extends Controller
{
    public function index()
    {
        $anggotas = User::where('role', 'member')->get();
        return view('pageadmin.manemegen_anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('pageadmin.manemegen_anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_anggota' => 'required|unique:users',
            'nama' => 'required',
            'username' => 'required|unique:users',
            'no_wa' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:6|confirmed',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'no_anggota' => $request->no_anggota,
            'nama' => $request->nama,
            'username' => $request->username,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role' => 'member',
        ];

        if ($request->hasFile('profil')) {
            $profil = $request->file('profil');
            $profilName = time() . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('profil'), $profilName);
            $data['profil'] = 'profil/' . $profilName;
        }

        $user = User::create($data);
        Alert::toast('Data berhasil ditambahkan', 'success')->position('top-end');
        return redirect()->route('manemegen_anggota.index');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('pageadmin.manemegen_anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_anggota' => 'required|unique:users,no_anggota,' . $id,
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'no_wa' => 'required',
            'alamat' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::findOrFail($id);
        $data = $request->except(['profil', 'password', 'confirm_password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profil')) {
            // Hapus foto lama jika ada
            if ($user->profil && file_exists(public_path($user->profil))) {
                unlink(public_path($user->profil));
            }

            $profil = $request->file('profil');
            $profilName = time() . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('profil'), $profilName);
            $data['profil'] = 'profil/' . $profilName;
        }

        $user->update($data);
        Alert::toast('Data berhasil diubah', 'success')->position('top-end');
        return redirect()->route('manemegen_anggota.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus foto jika ada
        if ($user->profil && file_exists(public_path($user->profil))) {
            unlink(public_path($user->profil));
        }
        
        $user->delete();
        Alert::toast('Data berhasil dihapus', 'success')->position('top-end');
        return redirect()->route('manemegen_anggota.index');
    }
}