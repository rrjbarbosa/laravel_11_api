<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\setores\SetoresEmUserUpdateGridPesquisaRequest;
use App\Http\Requests\setores\SetoresEmUserUpdateHabilitaDesabilitaRequest;
use App\Models\cadastro\Setor;
use App\Models\cadastro\SetorUser;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SetorUserController extends Controller
{
    #========================================================================
    public function grid(SetoresEmUserUpdateGridPesquisaRequest $request){
        $user       = $request->user();
        $requestes  = $request->validated();
        $userId     = Arr::pull($requestes, 'user_id');
        try{
            $setores = Setor::where('setors.grupo_empresar_id', $user->grupo_empresar_id)
                ->where('setors.ativo', 1)
                ->leftJoin('setor_users', function ($join) use ($userId) {
                    $join->on('setors.id', '=', 'setor_users.setor_id')
                        ->where('setor_users.user_id', '=', $userId);
                })
                ->where(function($query)use($requestes){
                    foreach ($requestes as $key=>$campo) {
                        $query->where($key, 'LIKE', '%' .$campo . '%');
                    }
                })      
                ->select(
                    'setors.id',
                    'setors.setor',
                    DB::raw('IF(setor_users.setor_id IS NULL, 0, 1) as ativo')
                )
                ->orderBy('setors.setor', 'ASC')
                ->get();
            
                return response()->json(['dados' => $setores]);
            }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }     
    }
    #=======================================================================
    public function update(SetoresEmUserUpdateHabilitaDesabilitaRequest $request){
        $user = $request->user(); // pega o usuário antes da transação

        try {
            DB::transaction(function () use ($request, $user) {
                SetorUser::where('user_id', $request->user_id)->delete();           
                $dadosArray = [];      
                if(count($request->all()) >= 1){  
                    foreach($request->input('setores') as $setor){                                        
                        array_push($dadosArray, [
                            'id'                => (string) Str::uuid(),                       
                            'setor_id'          => $setor,
                            'user_id'           => $request->user_id,           
                            'grupo_empresar_id' => $user->grupo_empresar_id
                        ]); 
                    }
                    DB::table('setor_users')->insert($dadosArray); // Salva dados no banco
                }
            });
            $setores = Setor::setoresPorUsuarios($request->user_id, $user->grupo_empresar_id);  
            return response([ 'status'=>'ok','mensagem' => '!!! Ok Salvo..', 'dados'=>$setores ]);

        } catch(\Exception $e){  
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }#========================================================================
    }
}    