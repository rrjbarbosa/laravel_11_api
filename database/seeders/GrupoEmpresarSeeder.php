<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\GrupoEmpresar;

class GrupoEmpresarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GrupoEmpresar::updateOrCreate(
            [ 'id' => '6a518c72-fbe5-11e9-91d8-d027888098e2' ],
            [
                'grupo_empresa' => 'Grupo de Empresa 1'
            ]
        );
        GrupoEmpresar::updateOrCreate(
            [ 'id' => '601de8e8-803e-11eb-a6f8-d027888098e2' ],
            [
                'grupo_empresa' => 'Grupo de Empresa 2'
            ]
        );
    }
}
