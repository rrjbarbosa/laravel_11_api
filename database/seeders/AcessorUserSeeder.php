<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\AcessorUser;

class AcessorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcessorUser::updateOrCreate(
            [ 'id' => '99av3e16-3327-12eb-a6df-0242a76971ef'],
            [
                'user_id'                 => '24e388f6-f2b3-11e9-af4f-00051660b717',
                'acessor_id'              => '88ab3e18-3323-11eb-a6dc-0242a76970ed',
                'grupo_empresar_id'       => '6a518c72-fbe5-11e9-91d8-d027888098e2',
            ]
         );
    }
}
