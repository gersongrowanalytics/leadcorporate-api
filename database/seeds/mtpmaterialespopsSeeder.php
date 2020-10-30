<?php

use Illuminate\Database\Seeder;
use App\mtpmaterialespops;

class mtpmaterialespopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        mtpmaterialespops::create([
            "mtpnombre" => "Cenefas",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/cenefas.png',
        ]);

        mtpmaterialespops::create([
            "mtpnombre" => "Jalavista",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/Jalavista.png',
        ]);

        mtpmaterialespops::create([
            "mtpnombre" => "Revestimiento de islas",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/revestimientoIslas.png',
        ]);

        mtpmaterialespops::create([
            "mtpnombre" => "Revestimiento de cabecera",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/revestimientoCabecera.png',
        ]);

        mtpmaterialespops::create([
            "mtpnombre" => "Rompetráfico",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/rompetrafico.png',
        ]);

        mtpmaterialespops::create([
            "mtpnombre" => "Punto de Caja",
            "mtpimagen" => env('APP_URL').'/sistema/img/materialesPops/puntoCaja.png',
        ]);
    }
}
