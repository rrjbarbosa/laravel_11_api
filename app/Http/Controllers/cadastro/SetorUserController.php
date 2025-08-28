<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Setor;
use App\Models\cadastro\SetorUser;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use App\Models\diversos\Funcoesr;
use App\Http\Requests\SetorUserRequest;

class SetorUserController extends Controller
{
    #========================================================================
    public function grid(Request $request){
        $user   = Auth::user();
        try{
            $dados = Setor::where('grupo_empresar_id','=', $user->grupo_empresar_id)
                            ->where(function($query)use($request){
                                $camposArray = ["setor"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })
                            ->select('id', 'ativo', 'setor')
                            ->get();
            return response()->json(['dados' => $dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }
    #=======================================================================
    public function createDelete(SetorUserRequest $request, Funcoesr $func){
       
        DB::beginTransaction();   #---Inicia a transsação----------------------------------------
        try{
                $user       = Auth::user();
                SetorUser::where('user_id', $request->idUsuario)->delete();            //-Deleta setores existentes           
                $dadosArray = []; 	                                                    //-Cria Array
                $setores = $request->setoresAraay;                                      //-Seta setores vindos do front
                if(count($setores) >= 1){                                               //-Se existir setores salva no banco  
                    foreach($setores as $setor){                                        //-Prepara array de dados para salvar 
                        array_push( $dadosArray,[   'id'                => $func->gerarUuid(),                       
                                                    'setor_id'          => $setor,
                                                    'user_id'           => $request->idUsuario,           
                                                    'grupo_empresar_id' => $user->grupo_empresar_id]); 
                    }
                
                    DB::table('setor_users')->insert($dadosArray);				        //-Salva dados no banco
                }
            DB::commit();             #---Efetiva as transações no Banco-----------------------------
                return response([ 'status'=>'ok','mensagem' => '!!! Ok Salvo..' ]);
        }
        catch(\Exception $e){  
            DB::rollBack();           #---Desfaz as transações---------------------------------------         
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================
}
