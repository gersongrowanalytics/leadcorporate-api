<?php

use Illuminate\Database\Seeder;
use App\tpetiposexhibicion;

class tpetiposexhibicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tpetiposexhibicion::create([
            "tpenombre" => "Islas",
        ]);

        tpetiposexhibicion::create([
            "tpenombre" => "Cabeceras",
        ]);

        tpetiposexhibicion::create([
            "tpenombre" => "Góndolas",
        ]);

        tpetiposexhibicion::create([
            "tpenombre" => "Checkout",
        ]);

        tpetiposexhibicion::create([
            "tpenombre" => "Cruzada",
        ]);

        tpetiposexhibicion::create([
            "tpenombre" => "Gancheras",
        ]);
    }
}
