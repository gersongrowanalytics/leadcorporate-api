<?php

use Illuminate\Database\Seeder;
use App\spasubpasos;

class spasubpasosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        spasubpasos::create([
            // "spaid"     = 1,
            "pasid"     => 1,
            "spanombre" => "Default",
        ]);

        spasubpasos::create([
            // "spaid"     = 2,
            "pasid"     => 2,
            "spanombre" => "2.1 Estado Inicial",
        ]);

        spasubpasos::create([
            // "spaid"     = 3,
            "pasid"     => 2,
            "spanombre" => "2.1 Estado Inicial",
        ]);

        spasubpasos::create([
            // "spaid"     = 4,
            "pasid"     => 2,
            "spanombre" => "2.3 Solicitar Faltante de altillo",
        ]);

        spasubpasos::create([
            // "spaid"     = 5,
            "pasid"     => 2,
            "spanombre" => "2.4 Medida de Góndolas",
        ]);

        spasubpasos::create([
            // "spaid"     = 6,
            "pasid"     => 3,
            "spanombre" => "3.1 Validar Stock",
        ]);

        spasubpasos::create([
            // "spaid"     = 7,
            "pasid"     => 3,
            "spanombre" => "3.2 LDP",
        ]);

        spasubpasos::create([
            // "spaid"     => 8,
            "pasid"     => 3,
            "spanombre" => "3.3 LDE",
        ]);

        spasubpasos::create([
            // "spaid"     => 9,
            "pasid"     => 4,
            "spanombre" => "Default",
        ]);

        spasubpasos::create([
            // "spaid"     => 10,
            "pasid"     => 5,
            "spanombre" => "Default",
        ]);

        spasubpasos::create([
            // "spaid"     => 11,
            "pasid"     => 6,
            "spanombre" => "Default",
        ]);
    }
}