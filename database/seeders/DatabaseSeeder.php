<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //---[PRODUÇÃO]-----------------
             /*ModuleTableSeeder::class,
              PermissionSeeder::class,
              ProducaoSeeder::class,*/

            //---[DESENVOLVIMENTO]----------
            GrupoEmpresarSeeder::class,
            UserSeeder::class,
            EmpresarSeeder::class,
            EmpresarUserSeeder::class,
            SetorTableSeeder::class,
            SetorUserTableSeeder::class,
            EstoquerSeeder::class,
            EstoquerUserSeeder::class,
            ModulorSeeder::class,
            PermissaorSeeder::class,
            AcessorSeeder::class,
            AcessorPermissaorSeeder::class,
            AcessorUserSeeder::class,
        ]);




        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
