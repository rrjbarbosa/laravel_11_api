<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acessor_user', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('user_id')->nullable(false);
            $table->uuid('acessor_id')->nullable(false);
            $table->uuid('grupo_empresar_id')->nullable(false);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('acessor_id')->references('id')->on('acessors');
            $table->foreign('grupo_empresar_id')->references('id')->on('grupo_empresars');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acessor_user');
    }
};
