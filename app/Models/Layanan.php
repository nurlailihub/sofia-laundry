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
        'tipe_harga',
        'keterangan',
    ];

    protected $casts = [
        'harga_per_kg' => 'decimal:2',
    ];

    /**
     * Label satuan berdasarkan tipe_harga
     */
    public function getSatuanLabelAttribute(): string
    {
        return $this->tipe_harga === 'satuan' ? 'pcs' : 'kg';
    }

    /**
     * Label harga untuk display (per kg / per pcs)
     */
    public function getHargaLabelAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_kg, 0, ',', '.')
            . ' / ' . $this->satuan_label;
    }

    // Relationships
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_layanan', 'id_layanan');
    }
}
