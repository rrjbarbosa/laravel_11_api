<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Acessor;
use App\Models\cadastro\AcessorUser;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use App\Models\diversos\Funcoesr;
use App\Http\Requests\AcessorUserRequest;
use App\Models\cadastro\Permissaor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AcessorUserController extends Controller
{
    #========================================================================
    public function grid(Request $request){
        $user   = Auth::user();
        try{
            $dados = Acessor::where(function($query)use($request){
                                $camposArray = ["acesso"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })
                            ->select('id', 'acesso')
                            ->get();
            return response()->json(['dados' => $dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }
    #========================================================================
    public function salvar(Request $request){
        $user = $request->user();
        try {
            DB::transaction(function () use ($request, $user) {
                $user_id    = $request->user_id; 
                $grupo      = $request->user()->grupo_empresar_id;   
               
                AcessorUser::where('user_id', $request->user_id)->delete();
                
                $dados = collect($request->acessos)->map(function($acessorId) use ($user_id, $grupo) {
                    return [
                        'id'                => Str::uuid()->toString(),
                        'user_id'           => $user_id,
                        'acessor_id'        => $acessorId,
                        'grupo_empresar_id' => $grupo,
                    ];
                })->toArray();

                AcessorUser::insert($dados);
            });
            $permissoes = Permissaor::permissoesPorUsuario($request->user_id);
              
            return response([ 'status'=>'ok','mensagem' => '!!! Ok Salvo..', 'permissoes'=>$permissoes]);

        } catch(\Exception $e){  
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }#========================================================================

    }
    #========================================================================    
    public function createDelete(AcessorUserRequest $request, Funcoesr $funcoes){
        DB::beginTransaction();   #---Inicia a transsação----------------------------------------
            try{        
                $user       = Auth::user();            
                AcessorUser::where('user_id', $request->idUser)->delete();              
                $dadosArray = []; 	                                                     //-Cria Array
                $acessos = $request->dados;                                              //-Setas acessos vindos do front
                if(count($acessos) >= 1){                                                //-Se existir acessos salva no banco  
                    foreach($acessos as $acesso){                                        //-Prepara array de dados para salvar 
                        array_push( $dadosArray,[   'id'                => $funcoes->gerarUuid(),                       
                                                    'user_id'           => $request->idUser,
                                                    'acessor_id'        => $acesso['id'],             
                                                    'grupo_empresar_id' => $user->grupo_empresar_id]);
                    }                                 
                }                
                DB::table('acessor_user')->insert($dadosArray);				 //-Salva dados no banco
                $permissoesUser = $user->arrayPermissaoUserNome($request->idUser);
        DB::commit();             #---Efetiva as transações no Banco-----------------------------
                return response(['status' => 'ok' , 'permissoesUser'=>$permissoesUser ]);
            }
            catch(\Exception $e){
        DB::rollBack();           #---Desfaz as transações---------------------------------------         
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
            }

    }#========================================================================
}
