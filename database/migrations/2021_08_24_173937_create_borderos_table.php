<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorderosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borderos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('codcedente')->nullable();
            $table->integer( 'agencia');
            $table->integer('contacorrente');
            $table->integer('numbanco');
            $table->string('nomebanco');
            $table->string('cnpjcredito');
            //$table->string('banco')->nullable();
            $table->date('assinatura')->nullable();
            $table->double('totalvlrface');
            $table->string('cnpjcontratante');
            $table->string('pixtipo')->nullable();
            $table->string('pixchave')->nullable();
            $table->date('operacao')->nullable();
            $table->string('contratante');
            $table->string('contratofomento')->nullable();
            $table->integer('qtddigitada');
            $table->date('dataop')->nullable();
            $table->string('proposta')->nullable();
            $table->string('statusbordero')->nullable();
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
        Schema::dropIfExists('borderos');
    }
}
