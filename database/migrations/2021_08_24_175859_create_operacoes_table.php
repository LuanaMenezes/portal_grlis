<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operacoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('razaosocial');
            $table->string('tipotitulo');
            $table->date('vcto');
            $table->double('vlrface');
            $table->integer('qtdetitulo');
            $table->string('ddd');
            $table->string('telefone');
            $table->string('bancooperacao')->nullable();
            $table->integer('digitooperacao')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('endop')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->string('emailoperacao')->nullable();
            $table->string('CMC7')->nullable();
            $table->string('C1')->nullable();
            $table->string('C2')->nullable();
            $table->string('C3')->nullable();
            $table->string('cnpjsacado')->nullable();
            $table->string('status')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operacoes');
    }
}
