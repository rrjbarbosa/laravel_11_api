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
        Schema::create('setors', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->tinyInteger('ativo')->default(1);
            $table->string('setor')->nullable(false);
            $table->mediumText('historico_edicao')->nullable(true);
            $table->uuid('grupo_empresar_id');
            $table->timestamps();

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
        Schema::dropIfExists('setors');
    }
};
