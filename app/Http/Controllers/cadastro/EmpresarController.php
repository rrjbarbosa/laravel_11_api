<?php

namespace App\Http\Controllers\cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\empresas\EmpresaEditRequest;
use App\Models\ErrorLog;
use App\Models\cadastro\Empresar;
use App\Models\cadastro\EmpresarUser;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\empresas\EmpresaEmUserUpdateHabilitaDesabilitaRequest; 
use App\Http\Requests\empresas\EmpresaEmUserUpdateGridRequest;
use App\Http\Requests\empresas\EmpresaGridRequest;
use App\Http\Requests\empresas\EmpresaHabilitaDesabilitaRequest;
use App\Http\Requests\empresas\EmpresaUpdate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EmpresarController extends Controller
{   
    public function empresas(EmpresaGridRequest $request){
        $user       = $request->user();
        try{
            $empresas = Empresar::where('grupo_empresar_id', $user->grupo_empresar_id)
                ->select(
                    'id',
                    'ativo',
                    'nome_fantasia',
                    'razao_social',
                    'cnpjOuCpf',
                    'cnpjCpf',
                    'cidade',
                    'bairro'
                )
                ->orderBy('nome_fantasia', 'ASC')
                ->get();
            
                return response()->json(['dados' => $empresas]);                
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor']);
        }        
    }#=======================================================================
    public function edit(EmpresaEditRequest $request){
        $user = $request->user(); 
        try{
            $empresa = Empresar::find($request->id);
            $empresa->imgBase64 = null;
            
            if($empresa->anexo_logomarca && Storage::disk('anexos')->exists($empresa->anexo_logomarca)) {
                $disk               = Storage::disk('anexos');                              //-Disco 'anexos' para recuperar a imagem, configurardo em config/filesystems.php
                $mimeType           = $disk->mimeType($empresa->anexo_logomarca);
                $imageContent       = $disk->get($empresa->anexo_logomarca);
                $imageBase64        = base64_encode($imageContent);
                $empresa->imgBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
            }            
            $empresa->makeHidden(['grupo_empresar_id', 'ativo', 'created_at', 'updated_at']);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['empresa'=>$empresa], 201);        
    }
    #=======================================================================
    public function habilitaDesabilita(EmpresaHabilitaDesabilitaRequest $request){
        $user           = $request->user(); 
        $empresa       = Empresar::find($request->id);
        $ativo          = $empresa->ativo == 1 ? 0 : 1;
        $request->acao  = $ativo == 1 ? 'Habilitou' : 'Desabilitou';    
        $historico      = json_decode($empresa->historico_edicao ?? '[]', true);
        array_push($historico, $this->historicoEmpresa($request));
        
        try{
            Empresar::where('id', $empresa->id)->update(['ativo'               => $ativo,
                                                           'historico_edicao'   => json_encode($historico)
                                                        ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...'], 201);        
    }#=======================================================================
    public function update(EmpresaUpdate $request){
        $user           = Auth::user();
        $empresa        = Empresar::find($request->id); 
        $request->acao  = 'Editado';
        $historico      = json_decode($empresa->historico_edicao ?? '[]', true);
        array_push($historico, $this->historicoEmpresa($request));
        try{
            if($request->imgNome){
                if ($empresa->anexo_logomarca && Storage::disk('anexos')->exists($empresa->anexo_logomarca)) {                          //-Deleta imagem se existir ao atualizar
                    Storage::disk('anexos')->delete($empresa->anexo_logomarca);
                }
                $imagemSalva = Storage::disk('anexos')->put($user->grupo_empresar_id . '/empresa' , $request->file('imgParaUpload'));   //-Atualiza a imagem
            }            
            Empresar::where('id', $request->id)
                ->update([
                    'anexo_logomarca'   => $request->imgNome ?  $imagemSalva : $empresa->anexo_logomarca,
                    'nome_fantasia'     => $request->nome_fantasia,
                    'razao_social'      => $request->razao_social,
                    'cnpjOuCpf'         => $request->cnpjOuCpf,
                    'cnpjCpf'           => $request->cnpjCpf,
                    'insc_estadual'     => $request->insc_estadual,
                    'insc_municipal'    => $request->insc_municipal,
                    'rua'               => $request->rua,
                    'numero'            => $request->numero,
                    'bairro'            => $request->bairro,
                    'cidade'            => $request->cidade,
                    'cep'               => $request->cep,
                    'uf'                => $request->uf,
                    'site'              => $request->site,
                    'email'             => $request->email,
                    'tel_um'            => $request->tel_um,
                    'tel_dois'          => $request->tel_dois,
                    'tel_tres'          => $request->tel_tres,         
                    'historico_edicao'  => json_encode($historico)        
            ]);
        }
        catch(\Exception $e){
            $erro = new ErrorLog($user, $e);
            return response(['status' => 'obs', 'mensagem' =>'Erro no servidor'], 500);
        }
        return response(['resultado'=>'Salvo com sucesso...'], 201);        
    }#=======================================================================
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
                    'empresars.cnpjCpf',
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
    private function historicoEmpresa($request){
        $historico = [
            'acao'             => $request->acao ,    
            'nome_fantasia'    => $request->nome_fantasia  ? $request->nome_fantasia   : null,
            'razao_social'     => $request->razao_social   ? $request->razao_social    : null,
            'cnpjOuCpf'        => $request->cnpjOuCpf      ? $request->cnpjOuCpf       : null,
            'cnpjCpf'          => $request->cnpjCpf        ? $request->cnpjCpf         : null,
            'insc_estadual'    => $request->insc_estadual  ? $request->insc_estadual   : null,
            'insc_municipal'   => $request->insc_municipal ? $request->insc_municipal  : null,
            'rua'              => $request->rua            ? $request->rua             : null,
            'numero'           => $request->numero         ? $request->numero          : null,
            'bairro'           => $request->bairro         ? $request->bairro          : null,
            'cidade'           => $request->cidade         ? $request->cidade          : null,
            'cep'              => $request->cep            ? $request->cep             : null,
            'uf'               => $request->uf             ? $request->uf              : null,
            'site'             => $request->site           ? $request->site            : null,
            'email'            => $request->email          ? $request->email           : null,
            'tel_um'           => $request->tel_um         ? $request->tel_um          : null,
            'tel_dois'         => $request->tel_dois       ? $request->tel_dois        : null,
            'tel_tres'         => $request->tel_tres       ? $request->tel_tres        : null,
            'data_hora'        => date('Y-m-d H:i:s'),
            'usuario'          => $request->user()->name,
        ];
        return $historico;
    }
}