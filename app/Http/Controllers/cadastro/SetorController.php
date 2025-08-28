<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SetorRequest;
use App\Models\cadastro\Setor; 
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;

class SetorController extends Controller
{  
    public function grid(Request $request){       
        try{
            $user           = Auth::user();

            $setores = Setor::where('grupo_empresar_id','=', $user->grupo_empresar_id)
                            ->where(function($query)use($request){
                                $camposArray = ["setor"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })
                            ->select('id', 'ativo', 'setor', 'historico_edicao')
                            ->OrderBy('setor', 'ASC')
                            ->get();

            return response()->json(['setores'=>$setores]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }     #========================================================================  
    public function create(SetorRequest $request){ 
        $user   = Auth::user();
        $historicoAtual = null;
        try{
        $setorCriar = Setor::create([  
                        'setor'             => $request->setor,
                        'ativo'             => 1,
                        'historico_edicao'  => $this->historico($historicoAtual, $user, $request),   
                        'grupo_empresar_id' => $user->grupo_empresar_id
                    ]);
            unset($setorCriar->grupo_empresar_id, $setorCriar->updated_at, $setorCriar->created_at); // Remove itens do obj         

        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
        return response([ 'status'=>'ok','mensagem' => 'Gerado com Sucesso', 'setorCriar' => $setorCriar]);
    }#======================================================================== 
    public function update(SetorRequest $request){ 
        $user           = Auth::user();
        $setor          = Setor::find($request->id);
        try{
            $atualiza = Setor::where('id', $request->id)
                            ->update([  
                                        'setor'             => $request->setor,
                                        'historico_edicao'  => $this->historico($setor->historico_edicao, $user, $request),
                                    ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
        
        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#======================================================================== 
    public function habilitaDesabilita(SetorRequest $request){  
        $user           = Auth::user();
        $setor          = Setor::find($request->id);
        try{
            $atualiza = Setor::where('id', $request->id)
                            ->update([  
                                        'ativo'             => $request->ativo,
                                        'historico_edicao'  => $this->historico($setor->historico_edicao, $user, $request),
                                    ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#======================================================================== 
    private function historico( $historicoAtual, $user, $request){
        switch ($request->acao) {
            case 'CRIAR':
                $historico = ['acao'=>'GERADO', 'setor'=>$request->setor, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')];
                break;
            case 'ATUALIZAR':
                $historico = json_decode($historicoAtual);
                array_push($historico, ['acao'=>'EDITADO', 'setor'=>$request->setor, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
            default:
                $historico = json_decode($historicoAtual);
                $acao = $request->ativo == 1 ? 'HABILITADO' : 'DESABILITADO';
                array_push($historico, ['acao'=>$acao, 'setor'=>$request->setor, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
        }              
        return json_encode($historico, JSON_UNESCAPED_SLASHES );
    }#========================================================================   
    private function setores(){
        return Setor::select('id', 'ativo', 'setor', 'historico_edicao')->orderBy('setor', 'ASC')->get();
         
    }#========================================================================     
   
}
