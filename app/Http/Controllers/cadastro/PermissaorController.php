<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissao\permissoesParaAcessoRequest;
use App\Models\cadastro\AcessorPermissaor;
use App\Models\cadastro\Modulor;
use Illuminate\Http\Request;
use App\Models\cadastro\Permissaor;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Microsoft\Graph\Generated\Models\Permission;
use Illuminate\Support\Str;

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
        sleep(3);
        $user = $request->user();
        try{
            DB::transaction(function () use ($request, $user) {
                $acesso_id    = $request->acesso_id; 
                $grupo      = $request->user()->grupo_empresar_id;   
               
                //AcessorPermissaor::where('user_id', $request->user_id)->delete();

                $permissoesModulos = Permissaor::select('id', 'modulor_id')->get();
                $permissoesModulos = json_decode($permissoesModulos); // O 'true' converte para array associativo

                
                $dados = collect($request->acessos)->map(function($permissaoId) use ($acesso_id, $grupo, $permissoesModulos) {
                    return [
                        'id'                => Str::uuid()->toString(),
                        'acessor_id'        => $acesso_id,
                        'permissaor_id'     => $permissaoId,
                        'modulor_id'        => collect($permissoesModulos)->where('id', $permissaoId)->pluck('modulor_id')->first(),
                        'grupo_empresar_id' => $grupo,
                    ];
                })->toArray();

                /*AcessorPermissaor::insert($dados);*/
                Log::info($permissoesModulos);
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
