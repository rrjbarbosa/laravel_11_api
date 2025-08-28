<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setor_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('setor_id');
            $table->uuid('user_id');
            $table->uuid('grupo_empresar_id');
            $table->timestamps();

            $table->foreign('setor_id')->references('id')->on('setors');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('setor_users');
    }
};
