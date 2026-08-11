<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barangs';
    protected $primaryKey = 'id_barang';
    
    protected $fillable = [
        'nama_barang',
        'satuan',
        'stok',
        'minimum_stok',
    ];

    // Relationships
    public function laporanStok()
    {
        return $this->hasMany(LaporanStok::class, 'id_barang', 'id_barang');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pewangi', 'id_barang');
    }

    // Check if stock is low
    public function isLowStock()
    {
        return $this->stok <= $this->minimum_stok;
    }
}
