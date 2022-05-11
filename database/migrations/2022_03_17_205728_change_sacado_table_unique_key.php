<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSacadoTableUniqueKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sacados', function (Blueprint $table) {
            $table->unique(["cedentecodigo", "cnpj"], 'sacado_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sacados', function (Blueprint $table) {
            $table->dropUnique('sacado_unique');
          });
    }
}
