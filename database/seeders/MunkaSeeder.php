<?php

namespace Database\Seeders;

use App\Models\Munka;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunkaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Munka::create([
            'kiindulasi_cim' => 'Budapest, Kossuth Lajos utca 1.',
            'erkezesi_cim' => 'Debrecen, Piac utca 10.',
            'cimzett_neve' => 'Kovács János',
            'cimzett_telefonszama' => '+36 30 123 4567',
            'statusz' => 'kiosztva',
            'fuvarozo_id' => null,
        ]);

        Munka::create([
            'kiindulasi_cim' => 'Szeged, Tisza Lajos körút 20.',
            'erkezesi_cim' => 'Pécs, Rákóczi út 15.',
            'cimzett_neve' => 'Nagy Anna',
            'cimzett_telefonszama' => '+36 20 987 6543',
            'statusz' => 'folyamatban',
            'fuvarozo_id' => 2, // fuvarozo@example.com
        ]);

        Munka::create([
            'kiindulasi_cim' => 'Győr, Baross Gábor út 5.',
            'erkezesi_cim' => 'Szombathely, Fő tér 8.',
            'cimzett_neve' => 'Szabó Péter',
            'cimzett_telefonszama' => '+36 70 555 1234',
            'statusz' => 'elvegezve',
            'fuvarozo_id' => 2,
        ]);

        Munka::create([
            'kiindulasi_cim' => 'Miskolc, Vörösmarty utca 12.',
            'erkezesi_cim' => 'Eger, Dobó István tér 3.',
            'cimzett_neve' => 'Tóth Márta',
            'cimzett_telefonszama' => '+36 30 777 8888',
            'statusz' => 'sikertelen',
            'fuvarozo_id' => 2,
        ]);
    }
}
