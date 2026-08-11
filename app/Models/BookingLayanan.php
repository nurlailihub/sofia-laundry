<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLayanan extends Model
{
    protected $table    = 'booking_layanans';
    protected $fillable = ['id_booking', 'id_layanan', 'estimasi_berat', 'estimasi_subtotal'];

    protected $casts = [
        'estimasi_berat'    => 'decimal:2',
        'estimasi_subtotal' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}
