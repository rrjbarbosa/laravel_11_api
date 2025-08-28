<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Setor;

class SetorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setor::updateOrCreate(
            ['id' =>'2ebbc202-33f9-11eb-b0aa-02424413da2f'],
            [
                'setor'             => 'desenvolvimento1',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
                'historico_edicao'  => '[{"0":"Setor","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","setor":"desenvolvimento1","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]'
            ]
        );
        Setor::updateOrCreate(
            ['id' =>'26d8c2e6-33fa-11eb-bba1-02424413da2f'],
            [
                'setor'             => 'desenvolvimento2',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
                'historico_edicao'  => '[{"0":"Setor","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","setor":"desenvolvimento2","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]'
            ]
        );
        Setor::updateOrCreate(
            ['id' =>'17362f28-b4aa-11eb-bcf6-202564843ae3'],
            [
                'setor'             => 'comercial',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
                'historico_edicao'  => '[{"0":"Setor","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","setor":"comercial","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]'
            ]
        );
        Setor::updateOrCreate(
            ['id' =>'3a7f59e6-b4aa-11eb-a1b0-202564843ae3'],
            [
                'setor'             => 'projeto',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
                'historico_edicao'  => '[{"0":"Setor","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","setor":"projeto","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]'
            ]
        );
    }
}
