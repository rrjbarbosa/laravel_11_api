<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Empresar;

class EmpresarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresar::updateOrCreate(
            [ 'id' => '3147f1a0-c058-11e9-9197-d027888098e2' ],
            [
                'nome_fantasia' => 'A1 Empresa um',
                'razao_social' => 'A1 Empresa um Ltda',
                'cnpj' => '1111111111',
                'insc_estadual' => '111',
                'insc_municipal' => '1111',
                'rua' => 'Rua Um',
                'numero' => '01',
                'bairro' => 'bairro um',
                'cidade' => 'cidade um',
                'cep' => '11111111',
                'uf' => '11',
                'site' => 'site_um.com,',
                'email' => 'email_um@email_um.com',
                'tel_um' => '1111',
                'tel_dois' => '1111',
                'tel_tres' => '1111',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );

        Empresar::updateOrCreate(
            [ 'id' => 'c3315c7c-231e-11ea-adc0-d027888098e2' ],
            [
               'nome_fantasia' => 'A2 Empresa dois',
                'razao_social' => 'A2 Empresa dois Ltda',
                'cnpj' => '2222222222',
                'insc_estadual' => '222',
                'insc_municipal' => '2222',
                'rua' => 'Rua Dois',
                'numero' => '02',
                'bairro' => 'bairro dois',
                'cidade' => 'cidade dois',
                'cep' => '22222222',
                'uf' => '22',
                'site' => 'site_dois.com,',
                'email' => 'email_dois@email_dois.com',
                'tel_um' => '22 222',
                'tel_dois' => '22 2222',
                'tel_tres' => '22 222222',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );

        Empresar::updateOrCreate(
            [ 'id' => 'a7a8f78a-5a54-11eb-a323-0242e71efb1b' ],
            [
                'nome_fantasia' => 'A3  Empresa treis Extrangeira',
                'razao_social' => 'A3 Empresa treis Ltda',
                'cnpj' => '3333333333332',
                'insc_estadual' => '333',
                'insc_municipal' => '33333',
                'rua' => 'Rua Tres',
                'numero' => '03',
                'bairro' => 'bairro Tres',
                'cidade' => 'cidade Tres',
                'cep' => '33333333',
                'uf' => '33',
                'site' => 'site_tres.com,',
                'email' => 'email_tres@email_tres.com',
                'tel_um' => '3333',
                'tel_dois' => '33333',
                'tel_tres' => '333333',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );

        Empresar::updateOrCreate(
            [ 'id' => '601de9ba-803e-11eb-9c03-d027888098e2' ],
            [
                'nome_fantasia' => 'A3  Empresa treis Extrangeira',
                'razao_social' => 'A3 Empresa treis Ltda',
                'cnpj' => '3333333333332',
                'insc_estadual' => '333',
                'insc_municipal' => '33333',
                'rua' => 'Rua Tres',
                'numero' => '03',
                'bairro' => 'bairro Tres',
                'cidade' => 'cidade Tres',
                'cep' => '33333333',
                'uf' => '33',
                'site' => 'site_tres.com,',
                'email' => 'email_tres@email_tres.com',
                'tel_um' => '3333',
                'tel_dois' => '33333',
                'tel_tres' => '333333',
                'grupo_empresar_id' => '601de8e8-803e-11eb-a6f8-d027888098e2',
            ]
        );
    }
}
