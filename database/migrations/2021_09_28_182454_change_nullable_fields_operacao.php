<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNullableFieldsOperacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operacoes', function(Blueprint $t) {
            $t->date('vcto')->nullable()->change();
            $t->string('tipotitulo')->nullable()->change();
            $t->string('ddd')->nullable()->change();
            $t->string('telefone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operacoes', function(Blueprint $t) {
            $t->date('vcto')->nullable()->change();
            $t->string('tipotitulo')->nullable()->change();
            $t->string('ddd')->nullable()->change();
            $t->string('telefone')->nullable()->change();
        });
    }
}
