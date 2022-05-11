<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->integer('codigo_ibge')->primary();
            $table->string('nome', 100);
            $table->float('latitude', 8);
            $table->float('longitude', 8);
            $table->boolean('capital');
            $table->integer('codigo_uf')->unsigned();
            $table->foreign('codigo_uf')->references('codigo_uf')->on('estados');
            $table->string('siafi_id', 4)->unique();
            $table->integer('ddd');
            $table->string('fuso_horario', 32);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
