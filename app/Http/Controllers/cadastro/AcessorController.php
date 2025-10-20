<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\acessos\AcessosCreateRequest;
use App\Http\Requests\acessos\AcessosDeleteRequest;
use App\Http\Requests\acessos\AcessosUpdateRequest;
use App\Models\cadastro\Acessor;

class AcessorController extends Controller
{
    public function criar(AcessosCreateRequest $request){
        $acesso = Acessor::create(
            ['acesso'           => $request->acesso,
            'grupo_empresar_id' => $request->user()->grupo_empresar_id]
        );
        $acessoCriado = [ 
                            'id'     => $acesso->id,
                            'acesso' => $acesso->acesso
                        ];

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso', 'acesso'=>$acessoCriado]);
    }#======================================================================== 
    public function editar(AcessosUpdateRequest $request){
        Acessor::where('id', $request->id)
                ->update([
                            'acesso' => $request->acesso
                        ]);

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }#======================================================================== 
    public function excluir(AcessosDeleteRequest $request){
        Acessor::where('id', $request->id)->delete();

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }
}
