<?php

use Illuminate\Database\Seeder;
use App\catcategorias;

class catcategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        catcategorias::create([
            "catcodigo" => "9379791251382",
            "catnombre" => "Higienicos FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/higienico.png'
        ]);

        catcategorias::create([
            "catcodigo" => "2642545914213",
            "catnombre" => "Toallas FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/toallas.png'
        ]);

        catcategorias::create([
            "catcodigo" => "3056416272743",
            "catnombre" => "Servilletas FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/servilletas.png'
        ]);

        catcategorias::create([
            "catcodigo" => "4",
            "catnombre" => "Paños FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/panios.png'
        ]);

        catcategorias::create([
            "catcodigo" => "5",
            "catnombre" => "Jabón FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/jabon.png'
        ]);

        catcategorias::create([
            "catcodigo" => "6",
            "catnombre" => "Facial FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/facial.png'
        ]);

        catcategorias::create([
            "catcodigo" => "7",
            "catnombre" => "Mascarilla FDH",
            "catimagen" => env('APP_URL').'/sistema/img/categorias/mascarilla.png'
        ]);
    }
}