<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Jarmu;
use App\Models\Munka;

class Fuvarozo extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'fuvarozo';

    protected $fillable = ['nev', 'email', 'jelszo', 'szerepkor'];

    protected $hidden = ['jelszo', 'remember_token'];

    protected function casts(): array
    {
        return [
            'jelszo' => 'hashed',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->jelszo;
    }

    public function jarmu()
    {
        return $this->hasMany(Jarmu::class);
    }

    public function munka()
    {
        return $this->hasMany(Munka::class);
    }
}
