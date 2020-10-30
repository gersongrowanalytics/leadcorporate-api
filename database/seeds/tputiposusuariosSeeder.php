<?php

use Illuminate\Database\Seeder;
use App\tputiposusuarios;

class tputiposusuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tputiposusuarios::create([
            "tpunombre" => "Administrador",
        ]);

        tputiposusuarios::create([
            "tpunombre" => "Distribuidora",
        ]);

        tputiposusuarios::create([
            "tpunombre" => "Mercaderista",
        ]);
    }
}
