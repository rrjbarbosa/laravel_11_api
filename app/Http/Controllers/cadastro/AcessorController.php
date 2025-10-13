<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Acessor;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

class AcessorController extends Controller
{
    public function criar(Request $request){
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
    public function editar(Request $request){
        Acessor::where('id', $request->id)
                ->update([
                            'acesso' => $request->acesso
                        ]);

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }#======================================================================== 
    public function excluir(Request $request){
        Acessor::where('id', $request->id)->delete();

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }
}
