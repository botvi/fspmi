<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfilAdminController extends Controller
{
    public function index()
    {
        $data = User::find(auth()->user()->id);
        return view('pageadmin.profil_admin.index', compact('data'));
    }

    public function update(Request $request)
    {
        $admin = User::find(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $admin->id,
            'jabatan' => 'required',
            'nama' => 'required', 
            'no_wa' => 'required',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alamat' => 'required',
            'password' => 'nullable|min:8'
        ]);

        if($validator->fails()) {
            Alert::toast('Terjadi kesalahan! ' . $validator->errors()->first(), 'error')->position('top-end');
            return redirect()->back();
        }

        if($request->hasFile('profil')) {
            // Hapus foto lama jika ada
            if ($admin->profil && file_exists(public_path('profil/' . $admin->profil))) {
                unlink(public_path('profil/' . $admin->profil));
            }

            $profil = $request->file('profil');
            $nama_profil = time() . '.' . $profil->getClientOriginalExtension();
            $profil->move(public_path('profil'), $nama_profil);
            $admin->profil = $nama_profil;
        }

        $data = $request->except(['profil', 'password']);
        
        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);
        Alert::toast('Profil berhasil diubah!', 'success')->position('top-end');
        return redirect()->back();
    }
}
