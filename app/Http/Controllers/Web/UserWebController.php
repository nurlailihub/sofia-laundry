<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserWebController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'customer')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_user'             => 'required|string|max:100',
            'username'              => 'required|string|max:50|unique:users,username',
            'password'              => 'required|string|min:6|confirmed',
            'role'                  => 'required|in:admin,pimpinan,customer',
        ]);

        User::create([
            'nama_user' => $validated['nama_user'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_user' => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users,username,' . $id . ',id_user',
            'password'  => 'nullable|string|min:6|confirmed',
            'role'      => 'required|in:admin,pimpinan,customer',
        ]);

        $data = [
            'nama_user' => $validated['nama_user'],
            'username'  => $validated['username'],
            'role'      => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
