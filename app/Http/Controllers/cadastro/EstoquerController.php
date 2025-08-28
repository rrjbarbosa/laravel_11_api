<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EstoquerRequest;
use App\Models\cadastro\Estoquer; 
use App\Models\cadastro\EstoquerUser;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;

class EstoquerController extends Controller
{
    public function grid(Request $request){       
        try{
            $estoques = Estoquer::where(function($query)use($request){
                $camposArray = ["estoque"];
                foreach ($camposArray as $campo) {
                    if($request->$campo){
                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                    }
                }
            })->select('id', 'ativo', 'estoque', 'historico_edicao', 'empresar_id')
              ->OrderBy('estoque', 'ASC')
              ->get();

            return response()->json(['estoque'=>$estoques]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================  
    public function create(EstoquerRequest $request){ 
        $user   = Auth::user();
        $historicoAtual = null;
        try{
            $estoqueCriar = Estoquer::create([  
                'estoque'           => $request->estoque,
                'ativo'             => 1,
                'historico_edicao'  => $this->historico($historicoAtual, $user, $request),
                'empresar_id'       => $request->empresar_id,       
                'grupo_empresar_id' => $user->grupo_empresar_id
            ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
        
        return response([ 'status'=>'ok','mensagem' => 'Gerado com Sucesso', 'estoqueCriado' => $estoqueCriar]);
    }#======================================================================== 
    public function update(EstoquerRequest $request){ 
        $user           = Auth::user();
        $estoque        = Estoquer::find($request->id);       
        try{
            $atualiza = Estoquer::where('id', $request->id)
                            ->update([  
                                        'estoque'           => $request->estoque,
                                        'historico_edicao'  => $this->historico($estoque->historico_edicao, $user, $request),
                                    ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
        
        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#======================================================================== 
    public function habilitaDesabilita(EstoquerRequest $request){ 
        $user           = Auth::user();
        $estoque        = Estoquer::find($request->id);
        try{
            $atualiza = Estoquer::where('id', $request->id)
                                ->update([  
                                        'ativo'             => $request->ativo,
                                        'historico_edicao'  => $this->historico($estoque->historico_edicao, $user, $request),
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
                $historico = ['acao'=>'GERADO', 'estoque'=>$request->estoque, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')];
                break;
            case 'ATUALIZAR':
                $historico = json_decode($historicoAtual);
                array_push($historico, ['acao'=>'EDITADO', 'estoque'=>$request->estoque, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
            case 'HABILITA_DESABILITA':
                $historico = json_decode($historicoAtual);
                $acao = $request->ativo == 1 ? 'HABILITADO' : 'DESABILITADO';
                array_push($historico, ['acao'=>$acao, 'estoque'=>$request->estoque, 'usuario'=>$user->name, 'dataHora' => date('Y-m-d H:i:s ')]);
                break;
        }              
        return json_encode($historico, JSON_UNESCAPED_SLASHES );
    }#========================================================================   
    private function estoques( $empresar_id){
        return Estoquer::where('empresar_id', $empresar_id)->select('id', 'ativo', 'estoque', 'historico_edicao', 'empresar_id')->orderBy('estoque', 'ASC')->get();
         
    }#========================================================================
}
