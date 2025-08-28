<?php

namespace App\Http\Controllers\comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

class ComercialEmailController extends Controller
{
    public function pesquisaCamposHead(Request $request){       
        try{
            $user   = Auth::user();
            // Configuração do servidor IMAP da Microsoft
            $hostname = '{imap.hostinger.com:993/imap/ssl}INBOX';
            $username = 'teste@intersistema.com.br'; // Seu e-mail
            $password = 'hH@030279@'; // Sua senha (ou token de app)

            // Conectar ao servidor IMAP
            $inbox = imap_open($hostname, $username, $password) or die('Erro ao conectar: ' . imap_last_error());

            // Buscar os e-mails não lidos
            $emails = imap_search($inbox, 'UNSEEN');

            if ($emails) {
                rsort($emails); // Organiza do mais recente ao mais antigo
                foreach ($emails as $email_number) {
                    $overview = imap_fetch_overview($inbox, $email_number, 0);
                    $message = imap_fetchbody($inbox, $email_number, 1);

                    echo "De: " . $overview[0]->from . "<br>";
                    echo "Assunto: " . $overview[0]->subject . "<br>";
                    echo "Mensagem: " . nl2br($message) . "<br><br>";
                }
            } else {
                echo "Nenhum e-mail encontrado.";
            }

            
            $folders = imap_list($inbox, '{imap.hostinger.com:993/imap/ssl}', '*');
            

            // Fechar a conexão
            imap_close($inbox);

            return response()->json(['folder'=>$folders, 'emails'=>$emails]);
            
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#======================================================================== 
}
