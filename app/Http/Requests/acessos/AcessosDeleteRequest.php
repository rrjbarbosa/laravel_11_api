<?php

namespace App\Http\Requests\acessos;

use App\Models\cadastro\Acessor;
use App\Models\cadastro\AcessorPermissaor;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AcessosDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
            $this->enviaErrosParaFront($msgsAuthorize, $inputs);
        }

        $acessosVinculadosEmPermissoes =   AcessorPermissaor::where('acessor_id', $this->id)->exists();
        if($acessosVinculadosEmPermissoes){  
            $msgsAuthorize[] = 'Existe Permissões vinculadas a esse Acesso, retire primeiro as permissões do Acesso';  
            $this->enviaErrosParaFront($msgsAuthorize, $inputs); 
        }

        $acessosGrupoEmpresa = Acessor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();
        if (!in_array($this->id, $acessosGrupoEmpresa)) { //- Se não for passado o acesso, ou se o acesso não fizer parte do grupo de empresa 
            $msgsAuthorize[] = 'O acesso não faz parte do seu grupo de empresa.';   
            $inputs->acesso = "";
            $this->enviaErrosParaFront($msgsAuthorize, $inputs);
        }
        
        $usuariosVinculadosAoAcesso = User::join('acessor_user', 'users.id', 'acessor_user.user_id')->where('acessor_user.acessor_id', $this->id)->pluck('users.name')->toArray();
        if (count($usuariosVinculadosAoAcesso) >= 1) { //- Não permite excluir se existir Acessos vinculados a usuários 
            $msgsAuthorize[] = '*** USUÁRIOS VINCULADOS A ESSE ACESSO, RETIRE O ACESSO DESSES USUÁRIOS PRIMEIRO ***';
            foreach($usuariosVinculadosAoAcesso AS $usuarios){
                $msgsAuthorize = [...$msgsAuthorize, $usuarios];    
            }
            $inputs->acesso = "";
            $this->enviaErrosParaFront($msgsAuthorize, $inputs);
        }

        return true;
    }

    public function enviaErrosParaFront($msgsAuthorize, $inputs){
        throw ValidationException::withMessages([
            'msgsAuthorize' => $msgsAuthorize,
            'inputs'        => $inputs,
        ]);
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function messages(): array
    {
        return [
            //
        ];
    }
}