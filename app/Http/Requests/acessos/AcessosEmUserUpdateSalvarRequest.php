<?php

namespace App\Http\Requests\acessos;

use App\Models\cadastro\Acessor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AcessosEmUserUpdateSalvarRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
        }

        if(!$this->user_id){
            array_push($msgsAuthorize, 'Usuário é obrigatório');   
        }

        $userEditado = User::find($this->user_id);
        if($this->user()->grupo_empresar_id != $userEditado->grupo_empresar_id){
            array_push($msgsAuthorize, 'Você não pode editar pemissões desse usuário');
        }

        $acessosGrupoEmpresa = Acessor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();
        if (!empty(array_diff($this->acessos, $acessosGrupoEmpresa))) { //- Se for passado algum acesso que não faz parte do grupo de empresa 
            $msgsAuthorize[] = 'Algum acesso não faz parte do grupo de empresa.';   
        }

        if(count($msgsAuthorize) > 0){
            throw ValidationException::withMessages([
                'msgsAuthorize' => $msgsAuthorize,
                'inputs'        => $inputs,
            ]);
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'acessos'   => 'array'
        ];
    }
}
