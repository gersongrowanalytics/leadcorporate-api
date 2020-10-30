<?php

use Illuminate\Database\Seeder;
use App\mecmecanicas;

class mecmecanicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mecmecanicas::create([
            "mecnombre" => "Tarjeta",
        ]);

        mecmecanicas::create([
            "mecnombre" => "Volumen de venta  X1",
        ]);

        mecmecanicas::create([
            "mecnombre" => "Volumen de venta  X20"
        ]);
    }
}
