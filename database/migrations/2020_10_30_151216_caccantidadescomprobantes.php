<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Caccantidadescomprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caccantidadescomprobantes', function (Blueprint $table) {
            $table->increments('cacid');
            $table->unsignedInteger('copid');
            $table->string('caccodigoean')->nullable();
            $table->string('caccantidad')->nullable();
            $table->timestamps();

            $table->foreign('copid')->references('copid')->on('copcomprobantespasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caccantidadescomprobantes');
    }
}
