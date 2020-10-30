<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Capcategoriasproveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capcategoriasproveedores', function (Blueprint $table) {
            $table->increments('capid');
            $table->unsignedInteger('prvid');
            $table->unsignedInteger('catid');
            $table->timestamps();

            $table->foreign('prvid')->references('prvid')->on('prvproveedores');
            $table->foreign('catid')->references('catid')->on('catcategorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capcategoriasproveedores');
    }
}