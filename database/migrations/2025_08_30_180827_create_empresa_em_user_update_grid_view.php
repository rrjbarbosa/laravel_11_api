<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW empresa_em_user_update_grid_view AS
            SELECT 
                empresars.id,
                empresars.ativo as status_empresa,
                empresars.nome_fantasia,
                empresars.cnpj,
                empresars.cidade,
                empresars.bairro,
                empresars.grupo_empresar_id,
                empresar_user.user_id,
                CASE
                    WHEN (
                        empresar_user.user_id IS NOT NULL
                    ) THEN 'ativo'
                    ELSE 'inativo'
                END AS ativo    

       
            FROM empresars
            LEFT JOIN empresar_user ON empresars.id = empresar_user.empresar_id
            ORDER BY empresars.nome_fantasia ASC;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_em_user_update_grid_view');
    }
};
