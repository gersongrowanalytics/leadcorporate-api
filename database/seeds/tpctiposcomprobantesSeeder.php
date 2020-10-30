<?php

use Illuminate\Database\Seeder;
use App\tpctiposcomprobantes;

class tpctiposcomprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tpctiposcomprobantes::create([
            "tpcnombre" => "Orden de compra",
        ]);

        tpctiposcomprobantes::create([
            "tpcnombre" => "Factura",
        ]);

        tpctiposcomprobantes::create([
            "tpcnombre" => "Guía de remisión",
        ]);

        tpctiposcomprobantes::create([
            "tpcnombre" => "Otros",
        ]);
    }
}
