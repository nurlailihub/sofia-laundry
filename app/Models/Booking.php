<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'kode_reservasi',
        'id_pelanggan',
        'id_layanan',
        'tanggal_booking',
        'waktu_booking',
        'estimasi_berat',
        'catatan',
        'tipe_antar_jemput',
        'alamat_jemput',
        'alamat_antar',
        'biaya_antar_jemput',
        'dp_bayar',
        'sisa_bayar',
        'status',
        'metode_bayar',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'estimasi_berat' => 'decimal:2',
        'biaya_antar_jemput' => 'decimal:2',
        'dp_bayar' => 'decimal:2',
        'sisa_bayar' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        // Generate kode_reservasi AFTER insert so we use the real id_booking
        // that was just assigned by the DB, avoiding race conditions entirely.
        static::created(function (Booking $booking) {
            if (!$booking->kode_reservasi) {
                $booking->updateQuietly([
                    'kode_reservasi' => 'RSV-' . str_pad($booking->id_booking, 4, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    public function layanans()
    {
        return $this->hasMany(BookingLayanan::class, 'id_booking', 'id_booking');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_booking', 'id_booking');
    }
}


