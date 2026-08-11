<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
    ];

    // Relationships
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'id_pelanggan', 'id_pelanggan');
    }
}
