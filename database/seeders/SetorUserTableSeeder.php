<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\SetorUser;

class SetorUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SetorUser::updateOrCreate(
            ['id' => 'd2cb3c8c-33fa-11eb-aeae-02424413da2f'],
            [
                'user_id' => '24e388f6-f2b3-11e9-af4f-00051660b717',
                'setor_id' => '2ebbc202-33f9-11eb-b0aa-02424413da2f',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );
        SetorUser::updateOrCreate(
            ['id' => 'd2cb3c8c-33fa-11eb-aeae-02424413da2g'],
            [
                'user_id' => '24e388f6-f2b3-11e9-af4f-00051660b717',
                'setor_id' => '3a7f59e6-b4aa-11eb-a1b0-202564843ae3',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );
        SetorUser::updateOrCreate(
            ['id' => 'd2cb3c8c-33fa-11eb-aeae-02424413da2h'],
            [
                'user_id' => '24e388f6-f2b3-11e9-af4f-00051660b717',
                'setor_id' => '26d8c2e6-33fa-11eb-bba1-02424413da2f',
                'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
        );
    }
}
