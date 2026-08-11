<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PelangganWebController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.pelanggans.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('admin.pelanggans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => ['required', 'string', 'max:15', 'regex:/^(08|628|62)[0-9]{8,13}$/'],
            'alamat'         => 'required|string',
            'buat_akun'      => 'nullable|boolean',
            'username'       => 'required_if:buat_akun,1|nullable|string|max:50|unique:users,username',
            'password'       => 'required_if:buat_akun,1|nullable|string|min:6',
            'password_confirmation' => 'required_if:buat_akun,1|nullable|string|same:password',
        ], [
            'no_hp.regex'            => 'Format nomor HP tidak valid. Gunakan format: 08xxx atau 628xxx',
            'username.required_if'   => 'Username wajib diisi jika membuat akun pelanggan.',
            'username.unique'        => 'Username sudah digunakan oleh user lain.',
            'password.required_if'   => 'Password wajib diisi jika membuat akun pelanggan.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_hp'          => $request->no_hp,
                'alamat'         => $request->alamat,
            ]);

            if ($request->boolean('buat_akun')) {
                $user = User::create([
                    'nama_user'    => $pelanggan->nama_pelanggan,
                    'username'     => $request->username,
                    'password'     => Hash::make($request->password),
                    'role'         => 'customer',
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                ]);
            }

            DB::commit();

            // Jika buat akun, redirect ke halaman kartu member
            if ($request->boolean('buat_akun')) {
                $token = encrypt($request->password);
                return redirect()->route('admin.register.customer.kartu', [
                    'id' => $user->id_user,
                    'tk' => $token,
                ]);
            }

            return redirect()->route('admin.pelanggans.index')
                ->with('success', 'Data pelanggan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pelanggan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => ['required', 'string', 'max:15', 'regex:/^(08|628|62)[0-9]{8,13}$/'],
            'alamat'         => 'required|string',
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid. Gunakan format: 08xxx atau 628xxx',
        ]);

        $pelanggan->update($request->only(['nama_pelanggan', 'no_hp', 'alamat']));

        return redirect()->route('admin.pelanggans.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::findOrFail($id);

            if ($pelanggan->user) {
                $pelanggan->user->delete();
            }

            $pelanggan->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data pelanggan beserta akun pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }
}
