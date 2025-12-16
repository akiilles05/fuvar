<?php

namespace Tests\Unit;

use App\Models\Munka;
use App\Models\Fuvarozo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNewJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new Munka (job).
     */
    public function test_create_new_job(): void
    {
        // Create a Fuvarozo (carrier) for the relationship
        $fuvarozo = Fuvarozo::create([
            'nev' => 'Test Fuvarozo',
            'email' => 'test@example.com',
            'jelszo' => 'password',
            'szerepkor' => 'fuvarozo',
        ]);

        // Data for the new job
        $jobData = [
            'kiindulasi_cim' => 'Budapest, Kossuth Lajos utca 1.',
            'erkezesi_cim' => 'Debrecen, Piac utca 10.',
            'cimzett_neve' => 'Kovács János',
            'cimzett_telefonszama' => '+36 30 123 4567',
            'statusz' => 'kiosztva',
            'fuvarozo_id' => $fuvarozo->id,
        ];

        // Create the job
        $munka = Munka::create($jobData);

        // Assert the job was created
        $this->assertInstanceOf(Munka::class, $munka);
        $this->assertEquals($jobData['kiindulasi_cim'], $munka->kiindulasi_cim);
        $this->assertEquals($jobData['erkezesi_cim'], $munka->erkezesi_cim);
        $this->assertEquals($jobData['cimzett_neve'], $munka->cimzett_neve);
        $this->assertEquals($jobData['cimzett_telefonszama'], $munka->cimzett_telefonszama);
        $this->assertEquals($jobData['statusz'], $munka->statusz);
        $this->assertEquals($jobData['fuvarozo_id'], $munka->fuvarozo_id);

        // Assert the relationship
        $this->assertInstanceOf(Fuvarozo::class, $munka->fuvarozo);
        $this->assertEquals($fuvarozo->id, $munka->fuvarozo->id);
    }

    /**
     * Test creating a job without fuvarozo_id.
     */
    public function test_create_job_without_carrier(): void
    {
        $jobData = [
            'kiindulasi_cim' => 'Szeged, Tisza Lajos körút 20.',
            'erkezesi_cim' => 'Pécs, Rákóczi út 15.',
            'cimzett_neve' => 'Nagy Anna',
            'cimzett_telefonszama' => '+36 20 987 6543',
            'statusz' => 'folyamatban',
        ];

        $munka = Munka::create($jobData);

        $this->assertInstanceOf(Munka::class, $munka);
        $this->assertNull($munka->fuvarozo_id);
        $this->assertNull($munka->fuvarozo);
    }

    /**
     * Test updating the carrier's status (szerepkor).
     */
    public function test_update_carrier_status(): void
    {
        // Create a Fuvarozo
        $fuvarozo = Fuvarozo::create([
            'nev' => 'Test Fuvarozo',
            'email' => 'test@example.com',
            'jelszo' => 'password',
            'szerepkor' => 'fuvarozo',
        ]);

        // Update the status to admin
        $fuvarozo->update(['szerepkor' => 'admin']);

        // Refresh from database
        $fuvarozo->refresh();

        // Assert the status was updated
        $this->assertEquals('admin', $fuvarozo->szerepkor);
    }
}
