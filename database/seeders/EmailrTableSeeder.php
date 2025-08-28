<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Emailr;

class EmailrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Emailr::updateOrCreate(
            ['id' =>'5rbbc301-33f9-11eb-b0aa-02424413dg8l'],
            [
                'email'             => 'a@a.com',
                'historico_edicao'  => '[{"0":"Email","1":"Usu\u00e1rio","2":"Data"},{"acao":"CRIAR","email":"a@a.com","usuario":"admin","dataHora":"2023-04-09 10:12:47 "}]',
                'empresar_id'       => '3147f1a0-c058-11e9-9197-d027888098e2',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2'
            ]
        );
    }
}
