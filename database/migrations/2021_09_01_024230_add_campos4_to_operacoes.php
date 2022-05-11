<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampos4ToOperacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operacoes', function (Blueprint $table) {
            $table->bigInteger('bordero_id')->unsigned();
            $table->foreign('bordero_id')->references('id')->on('borderos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operacoes', function (Blueprint $table) {
            $table->dropForeign('bordero_id_foreign');
            $table->dropColumn('bordero_id');
        });
    }
}
