<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissaors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissaors', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->string('nome')->nullable(false);
            $table->string('nome_exibicao')->nullable(false);
            $table->uuid('modulor_id');
            $table->timestamps();

            $table->foreign('modulor_id')->references('id')->on('modulors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissaors');
    }
}
