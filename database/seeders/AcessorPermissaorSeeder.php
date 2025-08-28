<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\AcessorPermissaor;
use App\Models\cadastro\Permissaor;
use App\Models\diversos\Funcoesr;

class AcessorPermissaorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Funcoesr $func)
    {
        $acessos = [];
        foreach(Permissaor::all() as $permissao){
            array_push( $acessos,[  'id'                    => $func->gerarUuid(), 
                                    'acessor_id'            => '88ab3e18-3323-11eb-a6dc-0242a76970ed',
                                    'permissaor_id'         => $permissao->id,
                                    'modulor_id'            => $permissao->modulor_id,
                                    'grupo_empresar_id'     => '6a518c72-fbe5-11e9-91d8-d027888098e2',     
                                ]);
        }
        AcessorPermissaor::insert($acessos);	


/*
        AcessorPermissaor::updateOrCreate(
            [ 'id' => '97a4de4e-3326-11eb-ba52-0242a76970ed'],
            [
                'acessor_id'             => '88ab3e18-3323-11eb-a6dc-0242a76970ed',
                'permissaor_id'          => '1b551524-2f3f-11eb-8d02-d027888098e2',
                'modulor_id'             => '0e26ecda-2f3d-11eb-9d5c-d027888098e2',
                'grupo_empresar_id'      => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
         );
*/

    }
}
