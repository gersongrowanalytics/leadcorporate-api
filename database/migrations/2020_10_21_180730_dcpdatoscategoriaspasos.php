<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dcpdatoscategoriaspasos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dcpdatoscategoriaspasos', function (Blueprint $table) {
            $table->increments('dcpid');
            $table->unsignedInteger('cpaid')->nullable();
            $table->unsignedInteger('capid')->nullable();
            $table->unsignedInteger('catid')->nullable();
            $table->unsignedInteger('mpaid')->nullable();
            $table->string('dcpancho')->nullable();
            $table->unsignedInteger('dsuid')->nullable();
            $table->string('dcpalto')->nullable();
            $table->string('dcpfrentes')->nullable();
            $table->unsignedInteger('mapid')->nullable();
            $table->unsignedInteger('marid')->nullable();
            $table->string('dcpproveedor')->nullable();
            $table->string('dcpnombre')->nullable();
            $table->string('dcpean')->nullable();
            $table->string('dcpprecioregular')->nullable();
            $table->string('dcpmarca')->nullable();
            $table->string('dcppromocion')->nullable();
            $table->string('dcppreciopromocion')->nullable();
            $table->string('dcpfechainicio')->nullable();
            $table->string('dcpfechafin')->nullable();
            $table->unsignedInteger('mecid')->nullable();
            $table->boolean('dcpestado')->default(false);
            $table->timestamps();

            $table->foreign('cpaid')->references('cpaid')->on('cpacategoriaspasos');
            $table->foreign('capid')->references('capid')->on('capcategoriasproveedores');
            $table->foreign('catid')->references('catid')->on('catcategorias');
            $table->foreign('mpaid')->references('mpaid')->on('mpamarcaspasos');
            $table->foreign('dsuid')->references('dsuid')->on('dsudatossubpasos');
            $table->foreign('mapid')->references('mapid')->on('mapmarcasproveedores');
            $table->foreign('marid')->references('marid')->on('marmarcas');
            $table->foreign('mecid')->references('mecid')->on('mecmecanicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dcpdatoscategoriaspasos');
    }
}
