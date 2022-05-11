<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSacadoTableDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sacados', function(Blueprint $t) {
            $t->boolean('ativo')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {  
        Schema::table('sacados', function(Blueprint $t) {
            $t->boolean('ativo')->default(1)->change();
        });
    }
}
