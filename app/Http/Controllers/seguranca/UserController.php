<?php

namespace App\Http\Controllers\seguranca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\diversos\Funcoesr;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Empresar;
use App\Models\cadastro\Permissaor;
use App\Models\cadastro\Acessor;
use App\Models\cadastro\Setor;
use App\Models\ErrorLog;
use App\Http\Requests\user\UserRequestEdit; 
use App\Http\Requests\user\UserRequestUpdate; 
use App\Http\Requests\user\UserRequestAdmin; 
use App\Http\Requests\user\UserRequestHabilitaDesabilita; 
use App\Http\Requests\user\UserRequestGridPesquisa; 
use App\Http\Requests\user\UserRequestCreate; 
use App\Http\Requests\user\UserRequestEditarSenha;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    
    #======================================================================== Register
    /*public function register(Request $request, Funcoesr $funcoes){
        $dados = $request->validate([
                'name'      => 'required|string',
                'email'     => 'required|string|unique:users,email',
                'password'  => 'required|string|confirmed'
        ]);
        $userLogado = Auth::user();
        $user = User::create([
                'id'                 => $funcoes->gerarUuid(),
                'name'               => $dados['name'],
                'email'              => $dados['email'],
                'password'           => bcrypt($dados['password']),
                'grupo_empresar_id'  => $userLogado->grupo_empresar_id

        ]);

        $token = $user->createToken('userlogado')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return response($response, 201);
    
    }#======================================================================== Login
    */
    public function login(Request $request){
        $dados = $request->validate([
                'email'     => 'required|string',
                'password'  => 'required|string'
        ]);

        $user = User::where('email', $dados['email'])->first();

        if(!$user || !Hash::check($dados['password'], $user->password)){
            return response(['message' =>'E-mail ou senha Inválidos'], 401);
        }

        if(!$user->ativo){
            return response(['message' =>'Esse login foi bloqueado'], 401);
        }

        $usuario        = (object) array('id' => $user->id, 'admin' => $user->admin, 'name' => $user->name, 'filtros_do_user' => $user->filtros_do_user);
        $token          = $user->createToken('tokenUsuario')->plainTextToken;
        $filtrosDoUser  = json_decode($user->filtros_do_user);    
        $response       = ['status' => "true",'usuario' => $usuario, 'token' => $token, "permissoes"=>$user->arrayPermissaoUserNome($user->id), "filtros_user"=>$filtrosDoUser];
        return response($response, 201);
    }#===================================================================================
    public function gridPesquisa(UserRequestGridPesquisa $request, Funcoesr $funcoes){
        $user   = Auth::user();

        try{
            $users = User::where('grupo_empresar_id', '=', $user->grupo_empresar_id)
                                    ->where(function($query)use($request){
                                        $camposArray = ["ativo", "name", "email"];
                                        foreach ($camposArray as $campo) {
                                            if($request->$campo){
                                                $query->where($campo, 'LIKE', '%' .$request->$campo . '%');
                                            }
                                        }
                                        $request->admin == true ? $query->where('admin', 'LIKE', '%' .'1'. '%') : null;
                                    })
                                    ->where('grupo_empresar_id', $request->user()->grupo_empresar_id)
                                    ->OrderBy('name', 'asc')
                                    ->select('id', 'ativo', 'name', 'email','email_envio_msg', 'admin')
                                    ->paginate($request->qtdPorPagina, ['*'], 'page', $request->page);
                                    
            $paginas = $funcoes->paginacao_paginas($users);
            return response()->json(['users' => $users, 'paginar' => $paginas]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }

    }#======================================================================== Create
    public function create(UserRequestCreate $request){
        $userLogado = Auth::user();
        $dados = User::create([
                                'name'               => $request->name,
                                'email'              => $request->email,
                                'email_envio_msg'    => $request->email_envio_msg,
                                'password'           => Hash::make($request->senha),
                                'grupo_empresar_id'  => $userLogado->grupo_empresar_id
                            ]);
        $dados = Arr::only($dados->toArray(), ['id','name', 'email', 'email_envio_msg']);  // - Selecionaos campos para retorno
        
        return response(['status'=>'ok', 'mensagem'=>'Gerado com Sucesso', 'dados'=>$dados]);
    }#======================================================================== Edit
    public function edit(UserRequestEdit $request){
        $user       = $request->user();
        $requestes  = $request->validated();

        //---[Usuário para edição]------------------------------------------------------------------------------------------
        $userEdicao     = User::where('grupo_empresar_id' ,$user->grupo_empresar_id)
                              ->select('id', 'name', 'email', 'email_envio_msg')
                              ->find($requestes['id']);
        
        $empresas       = Empresar::empresasPorUsuarios($requestes['id'], $user->grupo_empresar_id);
        $setores        = Setor::setoresPorUsuarios($requestes['id'], $user->grupo_empresar_id);                                
        $permissoes     = Permissaor::permissoesPorUsuario($requestes['id']);   
        $acessos        = Acessor::acessosPorUsuario($requestes['id'], $user->grupo_empresar_id);
        
        return response([   'usuario'           => $userEdicao,
                            'empresas'          => $empresas, 
                            'setores'           => $setores,
                            'permissoes'        => $permissoes,
                            'acessos'           => $acessos, 
        ]);
    }
    #======================================================================== Update
    public function update(UserRequestUpdate $request, $id){
        $user = User::where('id', $id)
                    ->where('grupo_empresar_id' ,$request->user()->grupo_empresar_id)
                    ->update($request->validated());

        return response([ 'status'=>'ok','mensagem' => 'Atualizado com Sucesso']);
    }#========================================================================
    public function admin(UserRequestAdmin $request, $id){
        $user = User::where('id', $id)
                    ->where('grupo_empresar_id' ,$request->user()->grupo_empresar_id)
                    ->update([ 'admin' => !$request->admin ]);

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }#========================================================================
    public function habilitaDesabilita(UserRequestHabilitaDesabilita $request, $id){ 
        $user = User::where('id', $id)
                    ->where('grupo_empresar_id' ,$request->user()->grupo_empresar_id)
                    ->update([ 'ativo' => !$request->ativo ]);

        return response([ 'status'=>'ok','mensagem' => 'Salvo com Sucesso']);
    }#========================================================================
    public function updateSeha(UserRequestEditarSenha $request){
        $user = User::where('id', $request->id)
                    ->update(['password' => Hash::make($request->senha)]);
        return response([ 'status'=>"ok", 'mensagem' => 'Senha Editada']);
    }#========================================================================
}
