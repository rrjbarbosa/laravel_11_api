<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissao\permissoesParaAcessoRequest;
use App\Models\cadastro\Modulor;
use Illuminate\Http\Request;
use App\Models\cadastro\Permissaor;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;

class PermissaorController extends Controller
{
    public function permissaoPorAcesso(permissoesParaAcessoRequest $request){
        $user = $request->user();
        try{
            $permissoes     = Permissaor::permissoesPorAcesso($request['acessor_id']);
            $modulos        = Modulor::all()->select('id', 'modulo');
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'],500);
        }
        return response()->json(['permissoes'=>$permissoes, 'modulos'=>$modulos], 200);
    }
    
    public function permissaoPorAcessoSalvar(Request $request){
        $user = $request->user();
        try{
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
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'],500);
        }
        return response()->json(['permissoes'=>$permissoes, 'modulos'=>$modulos], 200);
    }

    
}
