# laravel_11_api
Api rest Sanctum

## RODAR PROJETO AMBIENTE DESENVOLVIMENTO
composer dump-autoload && php artisan db:wipe && php artisan migrate:refresh --seed && php artisan serve

## CRIAR REQUEST
    php artisan make:request user\\UserRequestEditarSenha                                 

## LOG 
use Illuminate\Support\Facades\Log;

// Exemplos de níveis de log:
Log::debug('Mensagem de debug');
Log::info('Informação útil');
Log::warning('Aviso');
Log::error('Erro crítico');

Log::info('Usuário logado', ['id' => $user->id, 'email' => $user->email]);  //Passar dados no log: