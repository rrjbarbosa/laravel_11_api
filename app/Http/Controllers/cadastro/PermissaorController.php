<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissao\permissaoPorAcessoSalvarRequest;
use App\Http\Requests\permissao\permissoesParaAcessoRequest;
use App\Models\cadastro\AcessorPermissaor;
use App\Models\cadastro\Permissaor;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissaorController extends Controller
{
    public function permissaoPorAcesso(permissoesParaAcessoRequest $request){
        $user = $request->user();
        try{
            $permissoes     = Permissaor::permissoesPorAcesso($request['acessor_id']);
            
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'],500);
        }
        return response()->json(['permissoes'=>$permissoes], 200);
    }
    
    public function permissaoPorAcessoSalvar(permissaoPorAcessoSalvarRequest $request){
        $user = $request->user();
        try{
            DB::transaction(function () use ($request, $user) {
                AcessorPermissaor::where('acessor_id', $request->acessor_id)->delete();                                                  // Remove permissões antigas do mesmo acessor
                $acessor_id = $request->acessor_id; 
                $grupo     = $user->grupo_empresar_id;  
                $permissoesModulos = Permissaor::pluck('modulor_id', 'id')->toArray();                                                  // Busca todas permissões e converte para array associativo: id => modulor_id
                
                $dados = collect($request->permissoes)->map(function($permissaoId) use ($acessor_id, $grupo, $permissoesModulos) {       // Monta os dados para inserir
                    return [
                        'id'                => Str::uuid()->toString(),
                        'acessor_id'        => $acessor_id,
                        'permissaor_id'     => $permissaoId,
                        'modulor_id'        => $permissoesModulos[$permissaoId] ,
                        'grupo_empresar_id' => $grupo,
                    ];
                })->toArray();
                AcessorPermissaor::insert($dados);                                                                                      // Insere em lote (performático)
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
