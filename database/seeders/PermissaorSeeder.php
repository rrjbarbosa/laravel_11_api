<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadastro\Permissaor;

class PermissaorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // php artisan tinker - use Ramsey\Uuid\Uuid - echo Uuid::uuid4()
    public function run()
    {
        //EMPRESA==================================================================================
        Permissaor::updateOrCreate([ 'id' => '42d95bsw-3ed5-11dn-bb8d-024234a05382' ], ['nome' => 'cad_empresa_consultar',  'nome_exibicao' => 'Cad - Empresas - Consultar',    'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        //ADMIN====================================================================================
        Permissaor::updateOrCreate([ 'id' => 'acbc9d45-78fd-4ef0-25K0-568fe83db62S' ], ['nome' => 'administrador',          'nome_exibicao' => 'Administrador',    'modulor_id' => '0e26ecda-2f3d-11eb-9d5c-d027888098e2']);
        //SETOR==================================================================================
        Permissaor::updateOrCreate([ 'id' => 'd2d3d466-1256-41e5-afa5-f0f8b763ca4f' ], ['nome' => 'cad_setor_consultar',    'nome_exibicao' => 'Cad - Setor - Consultar',    'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => 'e6c84aa4-b173-40b8-8682-80e2cde9a4b6' ], ['nome' => 'cad_setor_editar',       'nome_exibicao' => 'Cad - Setor - Editar',       'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '4ba6c6e0-4a27-455d-ae2d-e6626ac7fa5b' ], ['nome' => 'cad_setor_criar',        'nome_exibicao' => 'Cad - Setor - Criar',        'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '33214dca-7c56-437b-b929-f562200e8ce5' ], ['nome' => 'cad_setor_deletar',      'nome_exibicao' => 'Cad - Setor - Deletar',      'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        //ESTOQUE==================================================================================
        Permissaor::updateOrCreate([ 'id' => 'acbc9d73-46fd-4ef0-92f0-437fe83db77d' ], ['nome' => 'cad_estoque_consultar',  'nome_exibicao' => 'Cad - Estoque - Consultar',    'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '8f60a388-1dae-42c4-a471-58f7d2199f7c' ], ['nome' => 'cad_estoque_editar',     'nome_exibicao' => 'Cad - Estoque - Editar',       'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => 'fd8272ee-26ea-4a9d-b2d8-3fa9efb073d1' ], ['nome' => 'cad_estoque_criar',      'nome_exibicao' => 'Cad - Estoque - Criar',        'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '253bad89-5225-4e10-be15-7d1d95cfe89b' ], ['nome' => 'cad_estoque_deletar',    'nome_exibicao' => 'Cad - Estoque - Deletar',      'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        //EMAIL====================================================================================
        Permissaor::updateOrCreate([ 'id' => 'acbc9d83-16fd-4ef0-92f0-437fe83db67d' ], ['nome' => 'cad_email_consultar',  'nome_exibicao' => 'Cad - Email - Consultar',    'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '8f60a378-2dae-42c4-a471-58f7d2199d7c' ], ['nome' => 'cad_email_editar',     'nome_exibicao' => 'Cad - Email - Editar',       'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => 'fd82726e-36ea-4a9d-b2d8-3fa9efb072d1' ], ['nome' => 'cad_email_criar',      'nome_exibicao' => 'Cad - Email - Criar',        'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
        Permissaor::updateOrCreate([ 'id' => '253bad59-4225-4e10-be15-7d1d95cfe79b' ], ['nome' => 'cad_email_deletar',    'nome_exibicao' => 'Cad - Email - Deletar',      'modulor_id' => '94ce3ef3-3edf-11ty-a790-024234a05381']);
    }
}
