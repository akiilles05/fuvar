<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jarmu extends Model
{
    protected $fillable = ['marka', 'tipus', 'rendszam', 'fuvarozo_id'];

    public function fuvarozo()
    {
        return $this->belongsTo(Fuvarozo::class);
    }
}
