<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acessors', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->string('acesso')->nullable(false);
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
        Schema::dropIfExists('acessors');
    }
}
