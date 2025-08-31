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
        Schema::create('estoquers', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->tinyInteger('ativo')->default(1);
            $table->string('estoque')->nullable(false);
            $table->mediumText('historico_edicao')->nullable(true);
            $table->uuid('empresar_id');
            $table->uuid('grupo_empresar_id');
            $table->timestamps();

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
        Schema::dropIfExists('estoquers');
    }
};
