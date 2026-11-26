<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'kolam_id',
        'kolam_nama',
        'recorded_at',
        'ph',
        'suhu',
        'cahaya',
        'turbidity',
        'tds',
        'biomassa',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'ph' => 'float',
        'suhu' => 'float',
        'cahaya' => 'integer',
        'turbidity' => 'float',
        'tds' => 'integer',
        'biomassa' => 'float',
    ];
}
