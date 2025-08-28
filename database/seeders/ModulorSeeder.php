<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Modulor;

class ModulorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modulor::updateOrCreate(
            [ 'id' => '0e26ecda-2f3d-11eb-9d5c-d027888098e2' ],
            [
                'modulo' => 'Seg - SEGURANÇA'
            ]
         );

         Modulor::updateOrCreate(
            [ 'id' => '65f86bca-3ecc-11eb-af7a-024234a05380' ],
            [
                'modulo' => 'Com - COMERCIAL'
            ]
        );

        Modulor::updateOrCreate(
            [ 'id' => '94ce3ef2-3ecc-11eb-a789-024234a05380' ],
            [
                'modulo' => 'Cus - CUSTOS'
            ]
        );

        Modulor::updateOrCreate(
            [ 'id' => '94ce3ef3-3edf-11ty-a790-024234a05381' ],
            [
                'modulo' => 'Cad - CADASTROS'
            ]
        );

        Modulor::updateOrCreate(
            [ 'id' => '5928af64-ba9e-11ec-ace6-129493f52e2c' ],
            [
                'modulo' => 'Qua - QUALIDADE'
            ]
        );
    }
}
