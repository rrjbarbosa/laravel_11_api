<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Acessor;
use App\Models\cadastro\AcessorPermissaor; 
use App\Models\cadastro\Permissaor; 
use App\Models\cadastro\Modulor;
use Illuminate\Support\Facades\DB;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AcessorRequest;

class AcessorController extends Controller
{
    public function pesquisaCamposHead(AcessorRequest $request){       
        try{
            $user   = Auth::user();
            $acessos = Acessor::where(function($query)use($request){
                $camposArray = ["acesso"];
                foreach ($camposArray as $campo) {
                    if($request->$campo){
                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                    }
                }
            })
            ->where('grupo_empresar_id', $user->grupo_empresar_id)
            ->OrderBy("acesso")
            ->get();

            $permissoes = Permissaor::OrderBy("nome_exibicao")->Get();
            $modulos    = Modulor::OrderBy("modulo")->Get();
            return response()->json(['acessos'=>$acessos, 'permissoes'=>$permissoes, 'modulos'=>$modulos]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#======================================================================== 
    public function edit(AcessorRequest $request){       
        try{
            $acessorPermissao = AcessorPermissaor::where('acessor_id','=',$request->id)
                                            ->pluck('permissaor_id')
                                            ->toarray();

            $acessorPermissaoObj = AcessorPermissaor::where('acessor_id','=',$request->id)->select('permissaor_id', 'modulor_id')->get();                                
        return response()->json(['acessorPermissao'=>$acessorPermissao, 'acessorPermissaoObj'=>$acessorPermissaoObj]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#======================================================================== 
    public function criar(AcessorRequest $request){
        $user   = Auth::user();
        $acesso = Acessor::create(
            ['acesso'           => $request->acesso,
            'grupo_empresar_id' => $user->grupo_empresar_id]
        );
        $acessoCriado = [ 
                            'id'     => $acesso->id,
                            'acesso' => $acesso->acesso
                        ];

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso', 'acesso'=>$acessoCriado]);
    }#======================================================================== 
    public function editar(AcessorRequest $request){
        Acessor::where('id', $request->id)
                ->update([
                            'acesso' => $request->acesso
                        ]);

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }#======================================================================== 
    public function excluir(AcessorRequest $request){
        Acessor::where('id', $request->id)->delete();

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }
}
