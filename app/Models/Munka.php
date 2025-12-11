<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Models\Fuvarozo;

class Munka extends Model
{
    protected $table = 'munka';

    protected $fillable = ['kiindulasi_cim', 'erkezesi_cim', 'cimzett_neve', 'cimzett_telefonszama', 'statusz', 'fuvarozo_id'];

    public function fuvarozo()
    {
        return $this->belongsTo(Fuvarozo::class);
    }

}
