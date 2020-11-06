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
        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "Gerson",
            "usucontrasenia" => Hash::make('Andrea$$Ezta$$'),
            "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "Gerson",
            "usucontrasenia" => Hash::make('Alessandro$$Mendoza$$'),
            "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        ]);

        usuusuarios::create([
            "tpuid"          => 1,
            "usuusuario"     => "Gerson",
            "usucontrasenia" => Hash::make('Juan$$Chumpe$$'),
            "usutoken"       => "HJ0t4xbw7zmQdZnpAyhffbMORIn8RpD9cUyRihQmXejkIOgmym6fuDOyWag0"
        ]);


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
    }
}
