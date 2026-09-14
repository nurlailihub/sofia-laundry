<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\StokBarang;
use App\Models\Transaksi;
use App\Models\User;
use App\Observers\AutoIncrementResetObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Reset AUTO_INCREMENT setelah setiap penghapusan data,
        // sehingga ID tidak loncat jauh dan tetap rapi.
        $observer = new AutoIncrementResetObserver();

        Transaksi::observe($observer);
        Booking::observe($observer);
        Pembayaran::observe($observer);
        Pelanggan::observe($observer);
        Layanan::observe($observer);
        StokBarang::observe($observer);
        User::observe($observer);
    }
}
