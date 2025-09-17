<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Setor;
use App\Models\cadastro\SetorUser;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;
use App\Models\diversos\Funcoesr;
use App\Http\Requests\SetorUserRequest;
use App\Models\cadastro\Empresar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetorUserController extends Controller
{
    #========================================================================
    public function grid(Request $request){
        $user   = Auth::user();
        try{
            $dados = Setor::where('grupo_empresar_id','=', $user->grupo_empresar_id)
                            ->where(function($query)use($request){
                                $camposArray = ["setor"];
                                foreach ($camposArray as $campo) {
                                    if($request->$campo){
                                        $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                    }
                                }
                            })
                            ->select('id', 'ativo', 'setor')
                            ->get();
            return response()->json(['dados' => $dados]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }
    #=======================================================================
    public function update(Request $request){
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