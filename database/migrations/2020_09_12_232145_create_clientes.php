<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 250);
            $table->string('cnpj', 20)->unique();
            $table->string('cga', 20)->unique();
            $table->char('uniprofissional', 1);
            $table->integer('qtd_socios')->nullable($value = true);
            $table->string('senha', 100); 
            $table->char('ativo')->default('S');
            $table->timestamps();
            $table->index('cnpj');
            $table->index('cga');
            $table->index('nome');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
