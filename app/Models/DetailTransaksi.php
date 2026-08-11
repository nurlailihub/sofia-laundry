<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id_detail';
    
    protected $fillable = [
        'id_transaksi',
        'id_layanan',
        'berat',
        'subtotal',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}
