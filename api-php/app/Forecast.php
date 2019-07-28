<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = ['lon', 'lat', 'response'];
    protected $casts = [
        'response' => 'array'
    ];
}
