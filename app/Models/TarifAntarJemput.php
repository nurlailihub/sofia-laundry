<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifAntarJemput extends Model
{
    protected $table    = 'tarif_antar_jemput';
    protected $fillable = ['tipe', 'label', 'harga', 'keterangan'];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public static array $tipeIcons = [
        'pickup'   => 'fas fa-hand-holding',
        'delivery' => 'fas fa-truck',
        'both'     => 'fas fa-exchange-alt',
    ];

    public static function getAll(): array
    {
        return static::all()->keyBy('tipe')->toArray();
    }

    public static function harga(string $tipe): float
    {
        return (float) static::where('tipe', $tipe)->value('harga') ?? 0;
    }
}
