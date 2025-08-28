<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Acessor;

class AcessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Acessor::updateOrCreate(
            [ 'id' => '88ab3e18-3323-11eb-a6dc-0242a76970ed'],
            [
                'acesso'         => 'Acesso Total',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
         );

         Acessor::updateOrCreate(
            [ 'id' => 'a57ad992-3ed4-11eb-a8a8-024234a05380'],
            [
                'acesso'         => 'COMERCIAL CUSTOS',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
         );
    }
}
