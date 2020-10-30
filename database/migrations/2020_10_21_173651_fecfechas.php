<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Fecfechas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fecfechas', function (Blueprint $table) {
            $table->increments('fecid');
            $table->integer('fecdia')->nullable();
            $table->integer('fecmes')->nullable();
            $table->integer('fecanio')->nullable();
            $table->dateTime('fecfecha')->nullable();
            $table->string('fecdiatexto')->nullable();
            $table->string('fecmestexto')->nullable();
            $table->string('fecaniotexto')->nullable();
            $table->string('fecfechatexto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fecfechas');
    }
}
