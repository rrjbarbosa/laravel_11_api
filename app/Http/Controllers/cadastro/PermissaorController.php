<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissao\permissoesParaAcessoRequest;
use App\Models\cadastro\Modulor;
use Illuminate\Http\Request;
use App\Models\cadastro\Permissaor;
use App\Models\ErrorLog;

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
}
