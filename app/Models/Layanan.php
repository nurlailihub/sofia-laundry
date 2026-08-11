<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanans';
    protected $primaryKey = 'id_layanan';
    
    protected $fillable = [
        'nama_layanan',
        'harga_per_kg',
        'keterangan',
    ];

    protected $casts = [
        'harga_per_kg' => 'decimal:2',
    ];

    // Relationships
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_layanan', 'id_layanan');
    }
}
