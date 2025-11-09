<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\setores\SetoresDesabilitarRemoverViculoComUsersRequest;
use App\Http\Requests\setores\SetoresEditRequest;
use App\Http\Requests\setores\SetoresGridRequest;
use App\Http\Requests\setores\SetoresHabilitaDesabilitaRequest;
use App\Http\Requests\setores\SetoresUpdateRequest;
use App\Models\cadastro\Setor;
use App\Models\cadastro\SetorUser;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class SetorController extends Controller
{
    #=======================================================================
    public function grid(SetoresGridRequest $request){
        $user       = $request->user();
        try{
            $setores = Setor::where('grupo_empresar_id', $user->grupo_empresar_id)
                ->select(
                    'id',
                    'ativo',
                    'setor'
                )
                ->orderBy('setor', 'ASC')
                ->get();
            
                return response()->json(['dados' => $setores]);                
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
    }#=======================================================================
    public function edit(SetoresEditRequest $request){
        $user = $request->user(); 
        try{
            $setores = Setor::find($request->id);
            $setores->makeHidden(['grupo_empresar_id', 'ativo', 'created_at', 'updated_at']);     //-Esconder esses campos na resposta
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['setores'=>$setores], 201);        
    }#=======================================================================
    public function update(SetoresUpdateRequest $request){
        $user           = $request->user();
        $setor          = Setor::find($request->id); 
        $request->acao  = 'Editado';
        $historico      = json_decode($setor->historico_edicao ?? '[]', true);
        array_push($historico, $this->historico($request));
        try{
            Setor::where('id', $request->id)
                ->update([
                    'setor'             => $request->setor ,
                    'historico_edicao'  => json_encode($historico)        
            ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...', 'historico_edicao'=>$historico], 201);        
    }
    #=======================================================================
    public function habilitaDesabilita(SetoresHabilitaDesabilitaRequest $request){
        $user           = $request->user(); 
        $setor          = Setor::find($request->id);
        $ativo          = $setor->ativo == 1 ? 0 : 1;
        $request->acao  = $ativo == 1 ? 'Habilitou' : 'Desabilitou';    
        $historico      = json_decode($setor->historico_edicao ?? '[]', true);
        array_push($historico, $this->historico($request));
        
        try{
            Setor::where('id', $setor->id)->update(['ativo'               => $ativo,
                                                     'historico_edicao'   => json_encode($historico)
                                             ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...'], 201);        
    }#=======================================================================
    public function DesabilitarRemoverVinculoUsuarios(SetoresDesabilitarRemoverViculoComUsersRequest $request){
        $user           = $request->user(); 
        $setor          = Setor::find($request->id);
        $request->acao  = 'Desabilitou';    
        $historico      = json_decode($setor->historico_edicao ?? '[]', true);
        array_push($historico, $this->historico($request));
        
        try{
            Setor::where('id', $setor->id)->update(['ativo'               => 0,
                                                     'historico_edicao'   => json_encode($historico)
                                             ]);
            SetorUser::where('setor_id', $setor->id)
                    ->where('grupo_empresar_id', $user->grupo_empresar_id)
                    ->delete();                                 
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...'], 201);        
    }#=======================================================================
    private function historico($request){
        $historico = [
            'acao'             => $request->acao ,    
            'setor'            => $request->setor  ? $request->setor   : null,
            'data_hora'        => date('Y-m-d H:i:s'),
            'usuario'          => $request->user()->name,
        ];
        return $historico;
    }
}
