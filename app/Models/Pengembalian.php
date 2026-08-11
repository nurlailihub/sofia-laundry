<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';
    protected $primaryKey = 'id_pengembalian';
    
    protected $fillable = [
        'id_transaksi',
        'id_booking',
        'tanggal_pengembalian',
        'status_pengembalian',
        'catatan',
        'notifikasi_terkirim',
        'tanggal_notifikasi',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'datetime',
        'tanggal_notifikasi' => 'datetime',
        'notifikasi_terkirim' => 'boolean',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Accessor untuk mendapatkan pelanggan melalui transaksi
    public function getPelangganAttribute()
    {
        return $this->transaksi->pelanggan ?? null;
    }

    // Accessor untuk mendapatkan total bayar termasuk biaya antar jemput jika transaksi berasal dari booking
    public function getTotalBayarAttribute()
    {
        $totalHarga = $this->transaksi->total_harga ?? 0;
        
        // Jika transaksi berasal dari booking, tambahkan biaya antar jemput
        if ($this->transaksi->id_booking && $this->transaksi->booking) {
            $biayaAntarJemput = $this->transaksi->booking->biaya_antar_jemput ?? 0;
            return $totalHarga + $biayaAntarJemput;
        }
        
        return $totalHarga;
    }
}
