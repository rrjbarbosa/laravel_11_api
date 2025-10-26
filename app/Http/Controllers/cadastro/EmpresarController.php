<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\cadastro\Empresar;
use App\Models\cadastro\EmpresarUser;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\empresas\EmpresaEmUserUpdateHabilitaDesabilitaRequest; 
use App\Http\Requests\empresas\EmpresaEmUserUpdateGridRequest;
use Illuminate\Support\Arr;


class EmpresarController extends Controller
{   public function empresaEmUserUpdateGrid(EmpresaEmUserUpdateGridRequest $request){
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
    }
}
