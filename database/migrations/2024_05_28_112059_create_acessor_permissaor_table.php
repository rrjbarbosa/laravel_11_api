<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessorPermissaorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acessor_permissaor', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('acessor_id');
            $table->uuid('permissaor_id');
            $table->uuid('modulor_id'); 
            $table->uuid('grupo_empresar_id');
            $table->timestamps();

            $table->foreign('acessor_id')->references('id')->on('acessors');
            $table->foreign('permissaor_id')->references('id')->on('permissaors');
            $table->foreign('modulor_id')->references('id')->on('modulors');
            $table->foreign('grupo_empresar_id')->references('id')->on('grupo_empresars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acessor_permissaor');
    }
}
