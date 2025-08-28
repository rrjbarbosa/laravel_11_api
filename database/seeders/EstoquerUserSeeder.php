<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\EstoquerUser;
use App\Models\cadastro\Estoquer;
use Ramsey\Uuid\Uuid;

class EstoquerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estoques = Estoquer::where('empresar_id', '3147f1a0-c058-11e9-9197-d027888098e2')->get();
        foreach($estoques AS $estoque){
            $id = Uuid::uuid4();
            EstoquerUser::create([
                'id'                  => $id,
                'estoquer_id'         => $estoque->id,
                'user_id'             => '24e388f6-f2b3-11e9-af4f-00051660b717',
                'empresar_id'         => '3147f1a0-c058-11e9-9197-d027888098e2',
                'grupo_empresar_id'   => '6a518c72-fbe5-11e9-91d8-d027888098e2'
            ]);
        }




        /*
        EstoquerUser::updateOrCreate(
            ['id' => 'd2cb3c8c-33fa-11eb-aeae-02424413da2f'],
            [
                'estoquer_id' => '2ebbc202-33f9-11eb-b0aa-02424413da2o',
                'user_id' => '24e388f6-f2b3-11e9-af4f-00051660b717',
                
                'empresar_id'=> '3147f1a0-c058-11e9-9197-d027888098e2',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );*/
    }
}
