<?php

use Illuminate\Database\Seeder;
use App\paspasos;

class paspasosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        paspasos::create([
            "pasnombre" => "Ingreso a Tienda",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/ingresoTienda.png',
        ]);

        paspasos::create([
            "pasnombre" => "Orden de góndola",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/ordenGondola.png',
        ]);

        paspasos::create([
            "pasnombre" => "Registro de información",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/registroInformacion.png',
        ]);

        paspasos::create([
            "pasnombre" => "Exhibición",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/exhibicion.png',
        ]);

        paspasos::create([
            "pasnombre" => "Entrega de mercadería",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/entregaMercaderia.png',
        ]);

        paspasos::create([
            "pasnombre" => "Salida de tienda",
            "pasimagen" => env('APP_URL').'/sistema/img/pasos/salidaTienda.png',
        ]);

    }
}