<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTransaksi extends Model
{
    protected $table = 'laporan_transaksis';
    protected $primaryKey = 'id_laporan';
    
    protected $fillable = [
        'periode',
        'total_transaksi',
        'total_pendapatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'total_pendapatan' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }
}
