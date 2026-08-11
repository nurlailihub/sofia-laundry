<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanStok extends Model
{
    protected $table = 'laporan_stoks';
    protected $primaryKey = 'id_laporan_stok';
    
    protected $fillable = [
        'id_barang',
        'tanggal_laporan',
        'stok_awal',
        'stok_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    // Relationships
    public function barang()
    {
        return $this->belongsTo(StokBarang::class, 'id_barang', 'id_barang');
    }
}
