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
        Schema::create('empresars', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->tinyInteger('ativo')->default(1);
            $table->string('anexo_logomarca')->nullable(true);
            $table->string('nome_fantasia')->nullable(false);
            $table->string('razao_social')->nullable(false);
            $table->string('cnpjOuCpf')->nullable(false);
            $table->string('cnpjCpf')->nullable(true);
            $table->string('insc_estadual')->nullable(true);
            $table->string('insc_municipal')->nullable(true);
            $table->string('rua')->nullable(true);
            $table->string('numero')->nullable(true);
            $table->string('bairro')->nullable(true);
            $table->string('cidade')->nullable(true);
            $table->string('cep')->nullable(true);
            $table->string('uf')->nullable(true);
            $table->string('site')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('tel_um')->nullable(true);
            $table->string('tel_dois')->nullable(true);
            $table->string('tel_tres')->nullable(true);
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
        Schema::dropIfExists('empresars');
    }
};
