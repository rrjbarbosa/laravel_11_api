<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Estoquer;
use App\Models\cadastro\EstoquerUser;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use App\Models\diversos\Funcoesr;
use App\Http\Requests\EstoquerUserRequest;

class EstoquerUserController extends Controller
{
    #========================================================================
    public function grid(Request $request){
        $user   = Auth::user();
        try{
            $dados = Estoquer::where('empresar_id','=', $request->idEmpresa)
                            ->where(function($query)use($request){
                                $camposArray = ["estoque"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })
                            ->select('id', 'ativo', 'estoque')
                            ->get();
            return response()->json(['dados' => $dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }
    #=======================================================================
    public function createDelete(EstoquerUserRequest $request, Funcoesr $func){
       
        DB::beginTransaction();   #---Inicia a transsação----------------------------------------
        try{
                $user       = Auth::user();
                EstoquerUser::where('user_id', $request->idUsuario)
                        ->where('empresar_id', $request->idEmpresa)->delete();          //-Deleta estoques existentes           
                $dadosArray = []; 	                                                    //-Cria Array
                $estoques = $request->estoquesAraay;                                     //-Seta estoques vindos do front
                if(count($estoques) >= 1){                                               //-Se existir estoques salva no banco  
                    foreach($estoques as $estoque){                                      //-Prepara array de dados para salvar 
                        array_push( $dadosArray,[   'id'                => $func->gerarUuid(),                       
                                                    'estoquer_id'       => $estoque,
                                                    'user_id'           => $request->idUsuario,
                                                    'empresar_id'       => $request->idEmpresa,            
                                                    'grupo_empresar_id' => $user->grupo_empresar_id]); 
                    }
                   
                    DB::table('estoquer_user')->insert($dadosArray);				        //-Salva dados no banco
                }
            DB::commit();             #---Efetiva as transações no Banco-----------------------------
                return response([ 'status'=>'ok','mensagem' => '!!! Ok Salvo.' ]);
        }
        catch(\Exception $e){  
            DB::rollBack();           #---Desfaz as transações---------------------------------------         
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================
}
