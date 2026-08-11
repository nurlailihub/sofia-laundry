<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table      = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan',
        'id_user',
        'id_pewangi',
        'id_booking',
        'tanggal_masuk',
        'tanggal_selesai',
        'total_berat',
        'total_harga',
        'tipe_antar_jemput',
        'biaya_antar_jemput',
        'status',
        'status_detail',
        'catatan_status',
    ];

    protected $casts = [
        'tanggal_masuk'      => 'datetime',
        'tanggal_selesai'    => 'datetime',
        'total_berat'        => 'decimal:2',
        'total_harga'        => 'decimal:2',
        'biaya_antar_jemput' => 'decimal:2',
    ];

    public static array $statusDetailLabels = [
        'menunggu'           => 'Menunggu Diproses',
        'diterima'           => 'Cucian Diterima',
        'dijemput'           => 'Dijemput Admin',
        'sedang_dicuci'      => 'Sedang Dicuci',
        'sedang_dikeringkan' => 'Sedang Dikeringkan',
        'sedang_disetrika'   => 'Sedang Disetrika',
        'sedang_dikemas'     => 'Sedang Dikemas',
        'siap_diambil'       => 'Siap Diambil',
        'selesai'            => 'Selesai / Sudah Diambil',
    ];

    public static array $statusDetailIcons = [
        'menunggu'           => 'fas fa-hourglass-start',
        'diterima'           => 'fas fa-box-open',
        'dijemput'           => 'fas fa-truck-pickup',
        'sedang_dicuci'      => 'fas fa-water',
        'sedang_dikeringkan' => 'fas fa-wind',
        'sedang_disetrika'   => 'fas fa-fire',
        'sedang_dikemas'     => 'fas fa-box',
        'siap_diambil'       => 'fas fa-check-circle',
        'selesai'            => 'fas fa-flag-checkered',
    ];

    public static array $statusDetailColors = [
        'menunggu'           => 'secondary',
        'diterima'           => 'info',
        'dijemput'           => 'primary',
        'sedang_dicuci'      => 'primary',
        'sedang_dikeringkan' => 'warning',
        'sedang_disetrika'   => 'danger',
        'sedang_dikemas'     => 'purple',
        'siap_diambil'       => 'success',
        'selesai'            => 'dark',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pewangi()
    {
        return $this->belongsTo(StokBarang::class, 'id_pewangi', 'id_barang');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_transaksi', 'id_transaksi');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_transaksi', 'id_transaksi');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }

    public function getBiayaAntarAttribute(): float
    {
        if ((float)($this->biaya_antar_jemput ?? 0) > 0) {
            return (float)$this->biaya_antar_jemput;
        }
        return (float)($this->booking?->biaya_antar_jemput ?? 0);
    }

    public function getTipeAntarAttribute(): string
    {
        if (!empty($this->tipe_antar_jemput) && $this->tipe_antar_jemput !== 'none') {
            return $this->tipe_antar_jemput;
        }
        return $this->booking?->tipe_antar_jemput ?? 'none';
    }

    public function getTotalTagihanAttribute(): float
    {
        return (float)$this->total_harga + $this->biaya_antar;
    }
}
