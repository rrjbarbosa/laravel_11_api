<?php

namespace App\Http\Controllers\seguranca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\diversos\Funcoesr;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Empresar;
use App\Models\cadastro\EmpresarUser;
use App\Models\cadastro\EmpresaEmUserUpdateGridView;
use App\Models\cadastro\Permissaor;
use App\Models\cadastro\Acessor;
use App\Models\cadastro\Setor;
use App\Models\cadastro\SetorUser;
use Illuminate\Support\Facades\DB;
use App\Models\ErrorLog;
use App\Http\Requests\user\UserRequestEdit; 
use App\Http\Requests\user\UserRequestUpdate; 
use App\Http\Requests\user\UserRequestAdmin; 
use App\Http\Requests\user\UserRequestHabilitaDesabilita; 
use App\Http\Requests\user\UserRequestGridPesquisa; 
use App\Http\Requests\user\UserRequestCreate; 
use App\Http\Requests\user\UserRequestEditarSenha; 
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $acessos        = Acessor::acessosPorUsuario($requestes['id']);
        
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
    public function ativaDesativaMultiplasTelas(Request $request){
        $user                    = Auth::user();
        $filtro                  = json_decode($user->filtros_do_user);                         //-decode transforma o Jsom em objeto
        empty($filtro) ? $filtro = json_decode('{}') : null;                                    //-Se for null cria um obj vazio
        $filtro->tela_individual = $request->telaIndividual;                                    //-Atualiza ou Cria e seta key e value no Obj
        $filtro->telas_multiplas = $request->telaMultipla;                                      //-Atualiza ou Cria e seta key e value no Obj
        $filtro                  = json_encode($filtro);                                        //-Transforma em string
        User::where('id', $user->id)->update(['filtros_do_user' => $filtro]);                   //-Atualiza banco)

        $user                    = User::find($user->id);                                       //-Inicia criação de novo token de user LOgado Para o Front
        $token                   = $user->createToken('tokenUsuario')->plainTextToken;
        $filtrosDoUser           = json_decode($user->filtros_do_user);
        $response                = ['status' => "true",'usuario' => $user, 'token' => $token, "permissoes"=>$user->arrayPermissaoUserNome($user->id),  "filtros_user"=>$filtrosDoUser];
        return response($response, 201);
    }#========================================================================
    public function updateSeha(UserRequestEditarSenha $request){
        $user = User::where('id', $request->id)
                    ->update(['password' => Hash::make($request->senha)]);
        return response([ 'status'=>"ok", 'mensagem' => 'Senha Editada']);
    }#========================================================================
    
    
    //========================================================================
    //==========================TESTE ENVIO EMAIL=============================
    //========================================================================
    public function emailLista(Request $request)
    {
        try{
            //- https://developer.microsoft.com/en-us/graph/graph-explorer      Demais rotas da API Outlook
            // Token de acesso obtido anteriormente
            $accessToken    = 'EwCYA8l6BAAUbDba3x2OMJElkF7gJ4z/VbCPEz0AAX/hhUTeYcciJ8JYIsUxOTvdNIhOj/QPjJOxbnrgXSrFAtL13O2/2S1cMLinjWdHdRyEp73aenww2YGelNnEvr8KsKbE83JEON0v5E7nejIKbI9kDNFyE/yZn9cE2LHplMfKgZTtJYBnKMT2utYZp17C8v+EoLw4AUX7xVMmAp3Ow9Pb7e7F1xsArURV18iO7NTXbDjdU5VW4KQvbySGp70mw3kdKOjunMcRkC2UnkxOXMXN51mqxfsYv5g5t2IrHovr13uDc8+lsoMbb1+92Ci/+8+A/B5obCG1u35859EcF8zD+WFU563CIRNr22nH+uDeysWaXC+9Xim0MugwES0QZgAAEAEOJPhGBhyY8p2UZ70dX3dgAjjsiqpU/HyZtdHWOj2D0Qq2Gwy/RkWSxrCLe4BZzmsA0s3trkb9VxsEb0oA6LHzwbULIKjHFS+Z0jhkRboGQUqcDpxKC2ET0ut0d1m0nA9HUEVFHTOvh9AjzyNaeU3kjrSnvh1md8Oq2GKmmibEQf8mxSTf/lkZ0kf690tKAXJtTmYUP2cpRuCuqfUsiXv/OOqTZh+rtz19/r3n9QdnzwJ+QQAhBrLDQ5ds0v/399OMGhElhMeCXiil3VifB49UMHmA0Mbxb8s9eRxhft85rvhcLKfCEDG1ilpFHhph27ObFg9dleKOAhHg74Vg9O207xHdCPG1M5bi1SfCSUDj0H9AtywSVgPYR2km4nTtNozMWfazrXph+n+LS0GGqf5eprFl4IWOuzT6CXTLh65YdOa/NrSJGuSe0eh5vGG5yw9bFwuFVigIGOOnO9OLEVlhLc3nPCnNRqWBnmo22Gdpa3AVx/O8bgrJC+F2siiKdqG5iQn33k2vCT3f7zM/sNvAzJlUwPCJoNsxR8ZeVihgc/nG5IAkIHk/EyRIwkIu//BAgf5F9P1QoEtdKwns17QpZWvYgDhNQ7e3qzjxCss99q4TMiCdr66ne7Im8s/sMFE4oJplaHef9b5ZUScWO7wqYOVMR9sPA7Nv+dU3RbddVzKc2ssteGNnHsktoT3TbXSJ741kLukUqQOzvgvqCQL91RHvXKGDnMzOZPOKOPIc06pwHAbX0AtLXMvuov3/+valTqW/B8HDSzAkdp/TuCzqj7USEdXjuGa0qRFks84/XceQd6YD6KSi/zwR9JjEYpu9mgI=';
            $url            = 'https://graph.microsoft.com/v1.0/me/messages';              // Endpoint do Microsoft Graph
            $ch             = curl_init();                                                 // Inicializando cURL
            
            curl_setopt($ch, CURLOPT_URL, $url);                                           // Configurando opções do cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);                                                    // Executa a requisição
            
            if (curl_errno($ch)) {                                                         // Verifica erros na execução
                echo 'Erro no cURL: ' . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode === 200) {                                                   // Sucesso: Decodificar a resposta JSON
                   $messages = json_decode($response, true);
                    return response([ 'status'=>"ok", 'mensagem' => $messages]);
                } else {                                                                   // Erro: Mostrar resposta
                    return response([ 'Erro: Código HTTP'=>$httpCode, 'Resposta' => $response]);
                }
            }
            curl_close($ch);                                                                // Fecha a conexão cURL
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }
    }

    public function emailListaDois(Request $request){ 
        
        // Configurações do servidor
        $host = '{imap-mail.outlook.com:993/imap/ssl}INBOX';
        $username = 'rrjbarbosa@hotmail.com';
        $password = '********';

        try {
            // Conexão com o servidor IMAP
            $imap = imap_open($host, $username, $password);

            if (!$imap) {
                throw new Exception('Não foi possível conectar: ' . imap_last_error());
            }

            // Obter os e-mails
            $emails = imap_search($imap, 'ALL');
            
            if ($emails) {
                rsort($emails); // Ordenar do mais recente para o mais antigo

                foreach ($emails as $email_number) {
                    $overview = imap_fetch_overview($imap, $email_number, 0);
                    $message = imap_fetchbody($imap, $email_number, 1);

                    echo "Assunto: " . $overview[0]->subject . "\n";
                    echo "De: " . $overview[0]->from . "\n";
                    echo "Mensagem: " . $message . "\n\n";
                }
            } else {
                echo "Nenhum e-mail encontrado.";
            }

            // Fechar a conexão
            imap_close($imap);
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
    
}
