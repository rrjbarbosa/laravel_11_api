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
        Schema::create('empresar_user', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();

            $table->uuid('empresar_id')->nullable(false);
            $table->uuid('user_id')->nullable(false);
            $table->uuid('grupo_empresar_id')->nullable(false);
            
            $table->timestamps();

            $table->foreign('empresar_id')->references('id')->on('empresars');
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
        Schema::dropIfExists('empresar_user');
    }
};
