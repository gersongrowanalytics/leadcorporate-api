<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cpacategoriaspasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpacategoriaspasos', function (Blueprint $table) {
            $table->increments('cpaid');
            $table->unsignedInteger('dsuid');
            $table->unsignedInteger('capid');
            $table->unsignedInteger('prvid');
            $table->unsignedInteger('catid');
            $table->boolean('cpaestado');
            $table->timestamps();

            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('capid')->references('capid')->on('capcategoriasproveedores');
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
        Schema::dropIfExists('cpacategoriaspasos');
    }
}
