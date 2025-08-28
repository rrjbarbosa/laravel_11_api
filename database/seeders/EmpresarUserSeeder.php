<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\EmpresarUser;

class EmpresarUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmpresarUser::updateOrCreate(
            [ 'id' => '1ce5ec70-c0ef-11e9-87ca-641c679d3741' ],
            [
                'empresar_id' => '3147f1a0-c058-11e9-9197-d027888098e2',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
                'user_id' => '24e388f6-f2b3-11e9-af4f-00051660b717'
            ]
        );
    }
}
