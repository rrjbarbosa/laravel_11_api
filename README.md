# laravel_11_api --------------------------------------------------------------------------------------------
Api rest Sanctum

## Criar View -----------------------------------------------------------------------------------------------
    php artisan make:migration create_view_empresa_em_user_update_grid
    php artisan migrate --path=/database/migrations/2025_08_15_150821_alter_clients_table_add_column_is_company_grupo.php

## RODAR PROJETO AMBIENTE DESENVOLVIMENTO -------------------------------------------------------------------
composer dump-autoload && php artisan db:wipe && php artisan migrate:refresh --seed && php artisan serve

## MODEL ----------------------------------------------------------------------------------------------------


## CRIAR REQUEST --------------------------------------------------------------------------------------------
    php artisan make:request user\\UserRequestEditarSenha                                 


## LOG ------------------------------------------------------------------------------------------------------
use Illuminate\Support\Facades\Log;

// Exemplos de níveis de log:
Log::debug('Mensagem de debug');
Log::info('Informação útil');
Log::warning('Aviso');
Log::error('Erro crítico');

Log::info('Usuário logado', ['id' => $user->id, 'email' => $user->email]);  //Passar dados no log: