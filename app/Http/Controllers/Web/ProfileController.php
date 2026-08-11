<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_user' => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        $data = ['nama_user' => $request->nama_user, 'username' => $request->username];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
