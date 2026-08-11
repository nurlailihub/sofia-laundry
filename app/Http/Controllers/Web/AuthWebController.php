<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }
        return view('auth.login');
    }

    public function showLoginAdmin()
    {
        return $this->showLogin();
    }

    public function showLoginCustomer()
    {
        return $this->showLogin();
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah.')->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectAfterLogin($user);
    }

    public function loginAdmin(Request $request)
    {
        return $this->login($request);
    }

    public function loginCustomer(Request $request)
    {
        return $this->login($request);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users,username',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:admin,pimpinan',
        ]);

        User::create([
            'nama_user' => $request->nama_user,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil didaftarkan.');
    }

    public function showRegisterCustomerForm()
    {
        $pelanggans = Pelanggan::whereDoesntHave('user')->orderBy('nama_pelanggan')->get();
        return view('auth.register-customer', compact('pelanggans'));
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan|unique:users,id_pelanggan',
            'username'     => 'required|string|max:50|unique:users,username',
            'password'     => 'required|string|min:6|confirmed',
        ], [
            'id_pelanggan.unique' => 'Pelanggan ini sudah memiliki akun.',
        ]);

        $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);

        $user = User::create([
            'nama_user'    => $pelanggan->nama_pelanggan,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => 'customer',
            'id_pelanggan' => $request->id_pelanggan,
        ]);

        // Enkripsi password lalu kirim via query string agar tidak bergantung session
        $token = encrypt($request->password);

        return redirect()->route('admin.register.customer.kartu', [
            'id' => $user->id_user,
            'tk' => $token,
        ]);
    }

    /**
     * Tampilkan halaman kartu member pelanggan.
     * Password dibaca dari query string terenkripsi (?tk=...).
     */
    public function kartuPelanggan(Request $request, $id)
    {
        $user      = User::with('pelanggan')->findOrFail($id);
        $pelanggan = $user->pelanggan;

        $password = '';
        if ($request->has('tk')) {
            try {
                $password = decrypt($request->query('tk'));
            } catch (\Exception $e) {
                $password = '';
            }
        }

        return view('admin.pelanggan-kartu', compact('user', 'pelanggan', 'password'));
    }

    /**
     * Halaman cetak kartu — standalone tanpa layout AdminLTE.
     * Password dibaca dari query string terenkripsi (?tk=...).
     */
    public function cetakKartu(Request $request, $id)
    {
        $user      = User::with('pelanggan')->findOrFail($id);
        $pelanggan = $user->pelanggan;

        $password = '';
        if ($request->has('tk')) {
            try {
                $password = decrypt($request->query('tk'));
            } catch (\Exception $e) {
                $password = '';
            }
        }

        return view('admin.pelanggan-kartu-print', compact('user', 'pelanggan', 'password'));
    }

    public function redirectAfterLogin(User $user)
    {
        return match ($user->role) {
            'customer' => redirect()->route('customer.dashboard'),
            'pimpinan' => redirect()->route('admin.laporan.transaksi.index'),
            default    => redirect()->intended(route('admin.dashboard')),
        };
    }
}
