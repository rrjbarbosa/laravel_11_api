<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Estoquer;

class EstoquerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estoquer::updateOrCreate(
            ['id' =>'2ebbc202-33f9-11eb-b0aa-02424413da2o'],
            [
                'estoque'           => 'central',
                'historico_edicao'  => '[{"0":"Estoque","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","estoque":"central","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]',
                'empresar_id'       => '3147f1a0-c058-11e9-9197-d027888098e2',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2'
            ]
        );
    }
}
