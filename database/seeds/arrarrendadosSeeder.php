<?php

use Illuminate\Database\Seeder;
use App\arrarrendados;

class arrarrendadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        arrarrendados::create([
            "arrnombre" => "Pagado",
        ]);

        arrarrendados::create([
            "arrnombre" => "Ganado",
        ]);
    }
}
