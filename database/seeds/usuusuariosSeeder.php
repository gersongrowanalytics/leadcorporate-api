<?php

use Illuminate\Database\Seeder;
use App\usuusuarios;
use Illuminate\Support\Facades\Hash;

class usuusuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Gerson",
        //     "usucontrasenia" => Hash::make('Andrea$$Ezta$$'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Gerson",
        //     "usucontrasenia" => Hash::make('Alessandro$$Mendoza$$'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Gerson",
        //     "usucontrasenia" => Hash::make('Juan$$Chumpe$$'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        // ]);


        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Gerson",
        //     "usucontrasenia" => Hash::make('1234'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Administrador",
        //     "usucontrasenia" => Hash::make('1234'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag1"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Eunice",
        //     "usucontrasenia" => Hash::make('1234'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag2"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "Miguel",
        //     "usucontrasenia" => Hash::make('1234'),
        //     "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag3"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "cintya.manrique",
        //     "usucontrasenia" => Hash::make('Cintya$$Manrique$$'),
        //     "usutoken"       => "Cyntia4xbw7zmQdZnpAyhffnriqun8RpD9cUyRihQmXejkIOgmym6fuDOyMan"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "esteban.mijares",
        //     "usucontrasenia" => Hash::make('Esteban$$Mijares$$'),
        //     "usutoken"       => "Esteban4xbw7zmQdZnpAyhffnriqunwertyuioppihQmXejkIOgmym6Mijare"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "ruth.portal",
        //     "usucontrasenia" => Hash::make('Ruth$$Portal$$'),
        //     "usutoken"       => "Ruthn4xbw7zmQdZnpAyhffnzxczxcxzcxzcxzqqihQmXejkIOgmym6Portal"
        // ]);

        // usuusuarios::create([
        //     "tpuid"          => 1,
        //     "usuusuario"     => "maria.yauri@softys.com",
        //     "usucontrasenia" => Hash::make('Maria$$Yauri$$'),
        //     "usutoken"       => "Maria4xbw7zmQdZnpAyhffnzxcz663zcxzqqihQmXejkIOgmym6PYauri"
        // ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "customer.prime@grow-analytics.com",
            "usucontrasenia" => Hash::make('Customer$$Prime$$'),
            "usutoken"       => "Customerbw7zmQdZnpAyhffnzxcz6772dzqzqqihQmXejkIOgmym6PPrime"
        ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "inteligencia.artificial@grow-analytics.com",
            "usucontrasenia" => Hash::make('Inteligencia$$Artificial$$'),
            "usutoken"       => "Inteligenciabw7zmQdZnpAyhffnzxczBhQmXejkIOgmym6PArtificial"
        ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "mia.aleman@grow-analytics.com",
            "usucontrasenia" => Hash::make('Mia$$Aleman$$'),
            "usutoken"       => "Miabw7zmQdZnpAyhffnzxczBhQmXqweasdggejkIOgmym6PAleman"
        ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "ada.caballero@grow-analytics.com",
            "usucontrasenia" => Hash::make('Ada$$Caballero$$'),
            "usutoken"       => "Adabw7zmQdZnpAyhffnzxczBhQmXqweasdggejkmym6PCabellero"
        ]);
    }
}
