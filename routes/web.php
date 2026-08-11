<?php

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BookingWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LayananWebController;
use App\Http\Controllers\Web\LaporanWebController;
use App\Http\Controllers\Web\MonitoringController;
use App\Http\Controllers\Web\PembayaranWebController;
use App\Http\Controllers\Web\PelangganWebController;
use App\Http\Controllers\Web\PengembalianWebController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\StokBarangWebController;
use App\Http\Controllers\Web\TarifAntarJemputController;
use App\Http\Controllers\Web\TransaksiWebController;
use App\Http\Controllers\Web\UserWebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    return match ($user->role) {
        'customer' => redirect()->route('customer.dashboard'),
        'pimpinan' => redirect()->route('admin.laporan.transaksi.index'),
        default    => redirect()->route('admin.dashboard'),
    };
})->name('home');

Route::get('/landing', [LandingController::class, 'index'])->name('landing.index');
Route::get('/booking/sukses', [LandingController::class, 'bookingSukses'])->name('landing.booking.sukses');
Route::get('/cek-status', [LandingController::class, 'cekStatusForm'])->name('landing.cek-status');
Route::post('/cek-status', [LandingController::class, 'cekStatus'])->name('landing.cek-status.post');

Route::get('/login', [AuthWebController::class, 'showLoginAdmin'])->name('login');
Route::post('/login', [AuthWebController::class, 'loginAdmin'])->name('login.post');

Route::get('/pelanggan/masuk', [AuthWebController::class, 'showLoginCustomer'])->name('login.customer');
Route::post('/pelanggan/masuk', [AuthWebController::class, 'loginCustomer'])->name('login.customer.post');

Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

Route::get('/password/reset', fn () => view('auth.passwords.email'))->name('password.request');
Route::post('/password/email', fn () => back()->with('status', 'Fitur reset password belum diaktifkan.'))->name('password.email');
Route::get('/password/reset/{token}', fn ($token) => view('auth.passwords.reset', ['token' => $token]))->name('password.reset');
Route::post('/password/reset', fn () => back()->with('status', 'Fitur reset password belum diaktifkan.'))->name('password.update');

Route::middleware(['auth', 'role:customer'])->prefix('pelanggan')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat', [CustomerDashboardController::class, 'riwayat'])->name('riwayat');
    Route::get('/booking', [CustomerDashboardController::class, 'bookingForm'])->name('booking');
    Route::post('/booking', [CustomerDashboardController::class, 'bookingStore'])->name('booking.store');
    Route::get('/transaksi/{id}', [CustomerDashboardController::class, 'detailTransaksi'])->name('transaksi.detail');
    Route::get('/transaksi/{id}/faktur', [CustomerDashboardController::class, 'fakturPembayaran'])->name('transaksi.faktur');
    Route::get('/transaksi/{id}/faktur/cetak', [CustomerDashboardController::class, 'cetakFaktur'])->name('transaksi.faktur.cetak');
});

Route::middleware(['auth', 'role:admin,pimpinan'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')
        ->middleware('role:admin');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profil/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/register', [AuthWebController::class, 'showRegisterForm'])->name('register')
        ->middleware('role:admin');
    Route::post('/register', [AuthWebController::class, 'register'])->name('register.store')
        ->middleware('role:admin');

    Route::get('/register-pelanggan', [AuthWebController::class, 'showRegisterCustomerForm'])->name('register.customer')
        ->middleware('role:admin');
    Route::post('/register-pelanggan', [AuthWebController::class, 'registerCustomer'])->name('register.customer.store')
        ->middleware('role:admin');
    Route::get('/register-pelanggan/kartu/{id}', [AuthWebController::class, 'kartuPelanggan'])->name('register.customer.kartu')
        ->middleware('role:admin');
    Route::get('/register-pelanggan/kartu/{id}/cetak', [AuthWebController::class, 'cetakKartu'])->name('register.customer.kartu.cetak')
        ->middleware('role:admin');

    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index')
        ->middleware('role:admin');
    Route::post('/monitoring/{id}/status', [MonitoringController::class, 'updateStatus'])->name('monitoring.update')
        ->middleware('role:admin');

    Route::get('/pelanggans', [PelangganWebController::class, 'index'])->name('pelanggans.index')
        ->middleware('role:admin');
    Route::get('/pelanggans/create', [PelangganWebController::class, 'create'])->name('pelanggans.create')
        ->middleware('role:admin');
    Route::post('/pelanggans', [PelangganWebController::class, 'store'])->name('pelanggans.store')
        ->middleware('role:admin');
    Route::get('/pelanggans/{id}/edit', [PelangganWebController::class, 'edit'])->name('pelanggans.edit')
        ->middleware('role:admin');
    Route::put('/pelanggans/{id}', [PelangganWebController::class, 'update'])->name('pelanggans.update')
        ->middleware('role:admin');
    Route::delete('/pelanggans/{id}', [PelangganWebController::class, 'destroy'])->name('pelanggans.destroy')
        ->middleware('role:admin');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserWebController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserWebController::class, 'create'])->name('users.create');
        Route::post('/users', [UserWebController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserWebController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserWebController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserWebController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/layanans', [LayananWebController::class, 'index'])->name('layanans.index')
        ->middleware('role:admin');
    Route::get('/layanans/create', [LayananWebController::class, 'create'])->name('layanans.create')
        ->middleware('role:admin');
    Route::post('/layanans', [LayananWebController::class, 'store'])->name('layanans.store')
        ->middleware('role:admin');
    Route::get('/layanans/{id}/edit', [LayananWebController::class, 'edit'])->name('layanans.edit')
        ->middleware('role:admin');
    Route::put('/layanans/{id}', [LayananWebController::class, 'update'])->name('layanans.update')
        ->middleware('role:admin');
    Route::delete('/layanans/{id}', [LayananWebController::class, 'destroy'])->name('layanans.destroy')
        ->middleware('role:admin');

    Route::get('/transaksis', [TransaksiWebController::class, 'index'])->name('transaksis.index')
        ->middleware('role:admin');
    Route::get('/transaksis/create', [TransaksiWebController::class, 'create'])->name('transaksis.create')
        ->middleware('role:admin');
    Route::post('/transaksis', [TransaksiWebController::class, 'store'])->name('transaksis.store')
        ->middleware('role:admin');
    Route::get('/transaksis/{id}/edit', [TransaksiWebController::class, 'edit'])->name('transaksis.edit')
        ->middleware('role:admin');
    Route::put('/transaksis/{id}', [TransaksiWebController::class, 'update'])->name('transaksis.update')
        ->middleware('role:admin');
    Route::delete('/transaksis/{id}', [TransaksiWebController::class, 'destroy'])->name('transaksis.destroy')
        ->middleware('role:admin');
    Route::get('/transaksis/{id}/faktur', [TransaksiWebController::class, 'faktur'])->name('transaksis.faktur')
        ->middleware('role:admin');
    Route::get('/transaksis/{id}/faktur/cetak', [TransaksiWebController::class, 'cetakFaktur'])->name('transaksis.cetak')
        ->middleware('role:admin');

    Route::get('/stok-barangs', [StokBarangWebController::class, 'index'])->name('stok_barangs.index')
        ->middleware('role:admin');
    Route::get('/stok-barangs/create', [StokBarangWebController::class, 'create'])->name('stok_barangs.create')
        ->middleware('role:admin');
    Route::post('/stok-barangs', [StokBarangWebController::class, 'store'])->name('stok_barangs.store')
        ->middleware('role:admin');
    Route::get('/stok-barangs/{id}/edit', [StokBarangWebController::class, 'edit'])->name('stok_barangs.edit')
        ->middleware('role:admin');
    Route::put('/stok-barangs/{id}', [StokBarangWebController::class, 'update'])->name('stok_barangs.update')
        ->middleware('role:admin');
    Route::delete('/stok-barangs/{id}', [StokBarangWebController::class, 'destroy'])->name('stok_barangs.destroy')
        ->middleware('role:admin');

    Route::get('/pengembalians', [PengembalianWebController::class, 'index'])->name('pengembalians.index')
        ->middleware('role:admin');
    Route::get('/pengembalians/create', [PengembalianWebController::class, 'create'])->name('pengembalians.create')
        ->middleware('role:admin');
    Route::post('/pengembalians', [PengembalianWebController::class, 'store'])->name('pengembalians.store')
        ->middleware('role:admin');
    Route::get('/pengembalians/{id}/edit', [PengembalianWebController::class, 'edit'])->name('pengembalians.edit')
        ->middleware('role:admin');
    Route::put('/pengembalians/{id}', [PengembalianWebController::class, 'update'])->name('pengembalians.update')
        ->middleware('role:admin');
    Route::delete('/pengembalians/{id}', [PengembalianWebController::class, 'destroy'])->name('pengembalians.destroy')
        ->middleware('role:admin');
    Route::post('/pengembalians/{id}/resend', [PengembalianWebController::class, 'resendNotification'])->name('pengembalians.resend')
        ->middleware('role:admin');

    Route::get('/pembayarans', [PembayaranWebController::class, 'index'])->name('pembayarans.index')
        ->middleware('role:admin');
    Route::get('/pembayarans/bayar/{id_transaksi}', [PembayaranWebController::class, 'create'])->name('pembayarans.create')
        ->middleware('role:admin');
    Route::post('/pembayarans', [PembayaranWebController::class, 'store'])->name('pembayarans.store')
        ->middleware('role:admin');
    Route::get('/pembayarans/faktur/{id}', [PembayaranWebController::class, 'faktur'])->name('pembayarans.faktur')
        ->middleware('role:admin');
    Route::get('/pembayarans/faktur/{id}/cetak', [PembayaranWebController::class, 'cetakFaktur'])->name('pembayarans.cetak')
        ->middleware('role:admin');
    Route::delete('/pembayarans/{id}', [PembayaranWebController::class, 'destroy'])->name('pembayarans.destroy')
        ->middleware('role:admin');

    Route::get('/tarif-antar', [TarifAntarJemputController::class, 'index'])->name('tarif.index')
        ->middleware('role:admin');
    Route::put('/tarif-antar/{id}', [TarifAntarJemputController::class, 'update'])->name('tarif.update')
        ->middleware('role:admin');

    Route::get('/bookings', [BookingWebController::class, 'index'])->name('bookings.index')
        ->middleware('role:admin');
    Route::get('/bookings/{id}/confirm', [BookingWebController::class, 'showConfirmForm'])->name('bookings.confirm.form')
        ->middleware('role:admin');
    Route::post('/bookings/{id}/confirm', [BookingWebController::class, 'confirm'])->name('bookings.confirm')
        ->middleware('role:admin');
    Route::delete('/bookings/{id}', [BookingWebController::class, 'destroy'])->name('bookings.destroy')
        ->middleware('role:admin');
    Route::post('/bookings/{id}/cancel', [BookingWebController::class, 'cancel'])->name('bookings.cancel')
        ->middleware('role:admin');
    Route::get('/bookings/{id}/faktur', [BookingWebController::class, 'faktur'])->name('bookings.faktur')
        ->middleware('role:admin');
    Route::get('/bookings/{id}/faktur/cetak', [BookingWebController::class, 'cetakFaktur'])->name('bookings.cetak')
        ->middleware('role:admin');
    Route::post('/bookings/{id}/bayar-dp', [BookingWebController::class, 'bayarDp'])->name('bookings.bayar-dp')
        ->middleware('role:admin');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/transaksi', [LaporanWebController::class, 'transaksiIndex'])->name('transaksi.index');
        Route::get('/transaksi/data', [LaporanWebController::class, 'transaksiData'])->name('transaksi.data');
        Route::get('/transaksi/cetak', [LaporanWebController::class, 'transaksiCetak'])->name('transaksi.cetak');

        Route::get('/pelanggan', [LaporanWebController::class, 'pelangganIndex'])->name('pelanggan.index');
        Route::get('/pelanggan/data', [LaporanWebController::class, 'pelangganData'])->name('pelanggan.data');
        Route::get('/pelanggan/cetak', [LaporanWebController::class, 'pelangganCetak'])->name('pelanggan.cetak');

        Route::get('/pertahun', [LaporanWebController::class, 'pertahunIndex'])->name('pertahun.index');
        Route::get('/pertahun/data', [LaporanWebController::class, 'pertahunData'])->name('pertahun.data');
        Route::get('/pertahun/cetak', [LaporanWebController::class, 'pertahunCetak'])->name('pertahun.cetak');
    });
});
