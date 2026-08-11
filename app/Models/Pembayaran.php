<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_transaksi',
        'tanggal_bayar',
        'metode_bayar',
        'jumlah_bayar',
        'nomor_referensi',
        'catatan',
        'status_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar'  => 'decimal:2',
    ];

    public static array $metodeLabels = [
        'cash'     => 'Bayar di Tempat (Cash)',
        'transfer' => 'Transfer Bank',
        'qris'     => 'QRIS',
    ];

    public static array $metodeIcons = [
        'cash'     => 'fas fa-money-bill-wave',
        'transfer' => 'fas fa-university',
        'qris'     => 'fas fa-qrcode',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function getNomorFakturAttribute(): string
    {
        return 'INV-' . str_pad($this->id_pembayaran, 6, '0', STR_PAD_LEFT)
            . '-' . $this->created_at->format('Ymd');
    }
}
