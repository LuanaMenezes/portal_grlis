<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivoTerceiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivo_terceiros', function (Blueprint $table) {
            $table->id();
            $table->string('path_arquivo');
            $table->bigInteger('terceiro_id')->unsigned();
            $table->foreign('terceiro_id')->references('id')->on('terceiros');
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
        Schema::dropIfExists('arquivo_terceiros');
    }
}


