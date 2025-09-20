<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Empresar;
use App\Models\cadastro\EmpresarUser; 
use App\Models\cadastro\Setor;
use App\Models\cadastro\Estoquer;
use App\Models\cadastro\EstoquerUser;
use App\Models\cadastro\Emailr;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\empresas\EmpresaEmUserUpdateHabilitaDesabilitaRequest; 
use App\Http\Requests\empresas\EmpresaEmUserUpdateGridRequest;
use Illuminate\Support\Arr;


class EmpresarController extends Controller
{   #========================================================================
    public function grid(Request $request){
        $user   = Auth::user();
        try{
            $dados = Empresar::where('grupo_empresar_id', '=', $user->grupo_empresar_id)
                            ->where(function($query)use($request){
                                $camposArray = ["nome_fantasia", "cnpj", "cidade", "bairro"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })->get();
            return response()->json(['dados' => $dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================
    public function create(Request $request){
        try{
            $user       = Auth::user();
            $cols       = ["nome_fantasia", "razao_social", "cnpj", "insc_estadual", "insc_municipal", "rua", "numero", "bairro", "cidade", "cep", "uf", "site", "email", "tel_um", "tel_dois", "tel_tres"];
            $arrayDados = [];
            foreach($cols as $col){$request[$col] ? $arrayDados[$col] = $request[$col] : $arrayDados[$col] = null;}
            $arrayDados["grupo_empresar_id"] = $user->grupo_empresar_id;
            
            $insere      = Empresar::create($arrayDados);
            return response([ 'status'=>'ok','mensagem' => 'Gerado com Sucesso', 'dados' => $insere]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }
    #=======================================================================
    public function update(Request $request){
        try{
                $user       = Auth::user();
                $cols       = ["nome_fantasia", "razao_social", "cnpj", "insc_estadual", "insc_municipal", "rua", "numero", "bairro", "cidade", "cep", "uf", "site", "email", "tel_um", "tel_dois", "tel_tres"];
                $arrayDados = [];
                foreach($cols as $col){$request[$col] ? $arrayDados[$col] = $request[$col] : $arrayDados[$col] = null;}
                $atualiza    = Empresar::where('id', $request->id)->update($arrayDados);
                return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso', 'dados' => $atualiza]);
        }
        catch(\Exception $e){           
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================
    public function habilitaDesabilita(Request $request){
            DB::beginTransaction();   #---Inicia a transsação----------------------------------------
        try{
                $user       = Auth::user();
                $atualiza    = Empresar::where('id', $request->id)->update(["ativo"=>$request->ativo]);
            DB::commit();             #---Efetiva as transações no Banco-----------------------------
            return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso', 'dados' => $atualiza]);
        }
        catch(\Exception $e){
            DB::rollBack();           #---Desfaz as transações---------------------------------------
                $erro = new ErrorLog($user, $e);
                return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }#========================================================================
    public function dadosSubForms(Request $request){
        $user    = Auth::user();
        $empresa = Empresar::find($request->id);
        $setor   = Setor::where('grupo_empresar_id','=', $user->grupo_empresar_id)->select('id', 'ativo', 'setor', 'historico_edicao')->orderBy('setor', 'ASC')->get();
        $estoque = Estoquer::where('empresar_id', $request->id)->select('id', 'ativo', 'estoque', 'historico_edicao', 'empresar_id')->orderBy('estoque', 'ASC')->get();
        $emails  = Emailr::where('empresar_id', $request->id)->select('id', 'ativo', 'email', 'historico_edicao', 'empresar_id')->orderBy('email', 'ASC')->get();

        return response(['status'=>'ok', 'empresa'=>$empresa, 'setor'=>$setor, 'estoque'=>$estoque, 'email'=>$emails]);
    }
    #=======================================================================
    #=======================================================================
    #=======================================================================
    public function empresaEmUserUpdateGrid(EmpresaEmUserUpdateGridRequest $request){
        $user       = $request->user();
        $requestes  = $request->validated();
        $userId     = Arr::pull($requestes, 'user_id');
        try{
            $empresas = Empresar::where('empresars.grupo_empresar_id', $user->grupo_empresar_id)
                ->where('empresars.ativo', 1)
                ->leftJoin('empresar_user', function ($join) use ($userId) {
                    $join->on('empresars.id', '=', 'empresar_user.empresar_id')
                        ->where('empresar_user.user_id', '=', $userId);
                })
                ->where(function($query)use($requestes){
                    foreach ($requestes as $key=>$campo) {
                        $query->where($key, 'LIKE', '%' .$campo . '%');
                    }
                })      
                ->select(
                    'empresars.id',
                    'empresars.nome_fantasia',
                    'empresars.cnpj',
                    'empresars.cidade',
                    'empresars.bairro',
                    'empresar_user.empresar_id',
                    DB::raw('IF(empresar_user.empresar_id IS NULL, 0, 1) as ativo')
                )
                ->orderBy('empresars.nome_fantasia', 'ASC')
                ->get();
            
                return response()->json(['dados' => $empresas]);                
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
    }#======================================================================= 
    public function empresaEmUserUpdateHabilitaDesabilita(EmpresaEmUserUpdateHabilitaDesabilitaRequest $request){
        $user       = $request->user(); 
        $requestes  = $request->validated();     

        try{
            if($requestes['ativo'] == 1){
                EmpresarUser::where('empresar_id', $requestes['id'])
                    ->where('user_id', $requestes['user_id'])
                    ->delete();
            }else{
                EmpresarUser::create([  
                    'empresar_id'           => $requestes['id'], 
                    'grupo_empresar_id'     => $user->grupo_empresar_id,   
                    'user_id'               => $requestes['user_id']
                ]);
            }
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...'], 201);
    }#======================================================================= 
    public function estoques(Request $request){
        $user              = Auth::user();    

        $estoques       = Estoquer::where('empresar_id','=', $request->empresar_id)
                                    ->select('id', 'ativo', 'estoque')
                                    ->orderBy('estoque', 'ASC')->get();

        $estoqueUser    = EstoquerUser::where('empresar_id','=', $request->empresar_id)
                                      ->where('user_id','=', $request->user_id)->pluck('estoquer_id')->toarray();

        return response(['status'=>'ok',    
                                            
                                            'estoquesObj'   => $estoques,
                                            'estoquesArray' => $estoqueUser]);
    }
}
