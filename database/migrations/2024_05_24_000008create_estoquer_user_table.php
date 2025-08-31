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
        Schema::create('estoquer_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('estoquer_id');
            $table->uuid('user_id');
            $table->uuid('empresar_id');
            $table->uuid('grupo_empresar_id');
            $table->timestamps();

            $table->foreign('estoquer_id')->references('id')->on('estoquers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('empresar_id')->references('id')->on('empresars');
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
        Schema::dropIfExists('estoquer_user');
    }
};
