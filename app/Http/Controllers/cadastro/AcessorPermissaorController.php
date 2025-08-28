<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\AcessorPermissaor;
use App\Models\cadastro\Permissaor;
use App\Models\cadastro\Acessor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use App\Models\diversos\Funcoesr;
use App\Http\Requests\AcessorPermissaorRequest;

class AcessorPermissaorController extends Controller
{
    #=======================================================================
    public function createDelete(AcessorPermissaorRequest $request, Funcoesr $func){
            DB::beginTransaction();   #---Inicia a transsação----------------------------------------
        try{
                $user       = Auth::user();
                AcessorPermissaor::where('acessor_id', $request->acessoId)->delete();    //-Deleta acesso existentes           
                $dadosArray = []; 	                                                     //-Cria Array
                $acessos = $request->acessoPermissoesAraayObj;                           //-Setas acessos vindos do front
                if(count($acessos) >= 1){                                                //-Se existir acessos salva no banco  
                    foreach($acessos as $acesso){                                        //-Prepara array de dados para salvar 
                        array_push( $dadosArray,[   'id'                  => $func->gerarUuid(),                       
                                                    'acessor_id'          => $request->acessoId,
                                                    'permissaor_id'       => $acesso['permissaor_id'],
                                                    'modulor_id'          => $acesso['modulor_id'],             
                                                    'grupo_empresar_id'   => $user->grupo_empresar_id]); 
                    }
                   
                    DB::table('acessor_permissaor')->insert($dadosArray);				 //-Salva dados no banco
                }
            DB::commit();             #---Efetiva as transações no Banco-----------------------------

                $permissoes     = Permissaor::select("nome", "nome_exibicao")
                                            ->orderBy('nome_exibicao', 'ASC')->get();
                $permissoesUser = $func->arrayPermissaoUserNome($request->user_id);
                
                $acessos        = Acessor::select('id', 'acesso')
                                        ->orderBy('acesso', 'ASC')->get();
                $acessosUser    = DB::table('acessors')
                                    ->join('acessor_user','acessors.id', '=', 'acessor_user.acessor_id')    
                                    ->where('acessor_user.user_id', '=', $request->user_id)
                                    ->select(['acessors.acesso'])
                                    ->pluck('acesso')->toarray();

                return response([   'status'            =>'ok',
                                    'mensagem'          => '!!! Ok Salvo',
                                    'permissoes'        => $permissoes,
                                    'permissoesUser'    => $permissoesUser,
                                    'acessos'           => $acessos, 
                                    'acessosUser'       => $acessosUser
                                ]);
        }
        catch(\Exception $e){  
            DB::rollBack();           #---Desfaz as transações---------------------------------------         
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================   
    public function permissoesModuloCreate(AcessorPermissaorRequest $request, Funcoesr $func){
        DB::beginTransaction();   #---Inicia a transsação----------------------------------------
        try{
                $user       = Auth::user();
                AcessorPermissaor::where('acessor_id', $request->acessoId)->where('modulor_id', $request->moduloId)->delete();    //-Deleta acesso existentes do módulo          
                $permissoesModulo = Permissaor::where('modulor_id', $request->moduloId)->get();
                
                $dadosArray = []; 	                                                     //-Cria Array
                foreach($permissoesModulo as $acesso){                                  //-Prepara array de dados para salvar 
                    array_push( $dadosArray,[   'id'                  => $func->gerarUuid(),                       
                                                'acessor_id'          => $request->acessoId,
                                                'permissaor_id'       => $acesso->id,
                                                'modulor_id'          => $request->moduloId,             
                                                'grupo_empresar_id'   => $user->grupo_empresar_id]); 
                }
                
                DB::table('acessor_permissaor')->insert($dadosArray);				 //-Salva dados no banco
                $acessorPermissao    = AcessorPermissaor::where('acessor_id','=',$request->acessoId)->pluck('permissaor_id')->toarray();
                $acessorPermissaoObj = AcessorPermissaor::where('acessor_id','=',$request->acessoId)->select('permissaor_id', 'modulor_id')->get(); 
                
            DB::commit();             #---Efetiva as transações no Banco-----------------------------
                return response([ 'status'=>'ok','mensagem' => '!!! Ok Salvo','acessorPermissao'=>$acessorPermissao, 'acessorPermissaoObj'=>$acessorPermissaoObj]);
        }
        catch(\Exception $e){  
            DB::rollBack();           #---Desfaz as transações---------------------------------------         
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#=======================================================================
}
