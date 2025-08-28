<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmailrRequest;
use App\Models\cadastro\Emailr; 
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;

class EmailrController extends Controller
{
    public function grid(Request $request){       
        try{
            $dados = Emailr::where(function($query)use($request){
                $camposArray = ["email"];
                foreach ($camposArray as $campo) {
                    if($request->$campo){
                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                    }
                }
            })->select('id', 'ativo', 'email', 'historico_edicao', 'empresar_id')
            ->OrderBy('email', 'ASC')
            ->get();

            return response()->json(['email'=>$dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }     #========================================================================
    public function create(EmailrRequest $request){ 
        $user   = Auth::user();
        $historicoAtual = null;
        try{
            $emailCriar = Emailr::create([  
                        'email'             => $request->email,
                        'ativo'             => 1,
                        'historico_edicao'  => $this->historico($historicoAtual, $user, $request),
                        'empresar_id'       => $request->empresar_id,       
                        'grupo_empresar_id' => $user->grupo_empresar_id
                    ]);
            unset($emailCriar->grupo_empresar_id, $emailCriar->updated_at, $emailCriar->created_at); // Remove itens do obj 
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
        
        return response([ 'status'=>'ok','mensagem' => 'Gerado com Sucesso', 'emailCriado'=>$emailCriar]);
    }
    #========================================================================
    public function update(EmailrRequest $request){ 
        $user           = Auth::user();
        $email          = Emailr::find($request->id);       
        try{
            $atualiza = Emailr::where('id', $request->id)
                            ->update([  
                                        'email'             => $request->email,
                                        'historico_edicao'  => $this->historico($email->historico_edicao, $user, $request),
                                    ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#========================================================================
    public function habilitaDesabilita(EmailrRequest $request){  
        $user           = Auth::user();
        $email          = Emailr::find($request->id);
        try{
            $atualiza = Emailr::where('id', $request->id)
                            ->update([  
                                        'ativo'             => $request->ativo,
                                        'historico_edicao'  => $this->historico($email->historico_edicao, $user, $request),
                                    ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#======================================================================== 
    private function historico( $historicoAtual, $user, $request){
        switch ($request->acao) {
            case 'CRIAR':
                $historico = [];
                array_push($historico, ['acao'=>'GERADO', 'email'=>$request->email, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
            case 'ATUALIZAR':
                $historico = json_decode($historicoAtual);
                array_push($historico, ['acao'=>'EDITADO', 'email'=>$request->email, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
            default:
                $historico = json_decode($historicoAtual);
                $acao = $request->ativo == 1 ? 'HABILITADO' : 'DESABILITADO';
                array_push($historico, ['acao'=>$acao, 'email'=>$request->email, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
        }              
        return json_encode($historico, JSON_UNESCAPED_SLASHES );
    }
    #========================================================================     
    private function emails( $empresar_id){
        return Emailr::where('empresar_id', $empresar_id)->select('id', 'ativo', 'email', 'historico_edicao', 'empresar_id')->orderBy('email', 'ASC')->get();
         
    }#========================================================================
}
