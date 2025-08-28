<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cadastro\Permissaor;
use App\Models\diversos\Funcoesr;
use Illuminate\Support\Facades\Auth;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\DB;

class PermissaorController extends Controller
{
    public function pesquisaCamposHead(Request $request, Funcoesr $funcoes){
        $user   = Auth::user();
        try{
            $permissoes = Permissaor::where(function($query)use($request){                                        
                                        $camposArray = ["nome_exibicao"];
                                        foreach ($camposArray as $campo) {
                                            if($request->$campo){                                                
                                                $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                            }
                                        }
                                    })->select("nome", "nome_exibicao")->orderBy('nome_exibicao', 'ASC')->get();
            return response()->json(['permissoes'=>$permissoes]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }

    } 
}
