<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSacadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sacado', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social');
            $table->string('cnpj');
            $table->string('cep');
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cidade');
            $table->char('estado', 2);
            $table->string('email');
            $table->string('ddd');
            $table->string('telefone');
            $table->string('cedentecodigo');
            $table->boolean('ativo');
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
        Schema::dropIfExists('sacado');
    }
}
