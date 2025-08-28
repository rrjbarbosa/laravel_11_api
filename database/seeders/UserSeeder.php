<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
		    [ 'id' => '24e388f6-f2b3-11e9-af4f-00051660b717' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'adriano a',
				'email'    			=> 'a@a.com',
				'email_envio_msg'	=> 'a@a.com',
				'password'			=> '12345678',
				'admin'				=> '1',
				'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
		);

		User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b4-11e9-8dd3-00051660b801' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'adriano b',
				'email'    			=> 'b@b.com',
				'email_envio_msg'	=> 'b@b.com',
				'password'			=> '12345678',
				'admin'				=> '1',
				'grupo_empresar_id' => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );

		User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2c8-11b5-8df8-00051660b985' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'teste',
				'email'    			=> 'teste@teste.com',
				'email_envio_msg'	=> 'teste@teste.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );

		User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b802' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin1@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );

		User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b6-11e9-8dd5-00051660b803' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin2@admin.com',
				'email_envio_msg'	=> 'admin2@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '601de8e8-803e-11eb-a6f8-d027888098e2'
		    ]
        );

        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b813' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin12@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b814' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin13@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b815' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin14@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b816' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin15@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b817' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin16@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b818' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin17@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b819' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin18@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b820' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin19@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b821' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin111@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b822' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin112@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b823' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin113@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b824' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin114@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b825' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin115@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b826' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin116@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b827' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin117@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b828' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin118@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b829' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin119@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b830' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin121@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b831' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin122@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b832' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin123@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
        User::updateOrCreate(
		    [ 'id' => '3e8a46fa-f2b5-11e9-8dd4-00051660b833' ],
		    [
				'ativo'				=> '1',
				'name'     			=> 'admin',
				'email'    			=> 'admin124@admin.com',
				'email_envio_msg'	=> 'admin1@admin.com',
				'password'			=> '12345678',
				'grupo_empresar_id'  => '6a518c72-fbe5-11e9-91d8-d027888098e2'
		    ]
        );
    }
}
