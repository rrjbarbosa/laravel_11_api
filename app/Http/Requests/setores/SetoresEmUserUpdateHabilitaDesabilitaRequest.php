<?php

namespace App\Http\Requests\setores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\cadastro\Setor;
use App\Models\User;

class SetoresEmUserUpdateHabilitaDesabilitaRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            $msgsAuthorize[] = 'Você não tem permissão de Administrador.';    
        }

        if(!$this->user_id){
            array_push($msgsAuthorize, 'Usuário é obrigatório');   
        }

        $setoresGrupoEmpresa = Setor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();
        if (!empty(array_diff($this->setores, $setoresGrupoEmpresa))) { //- Se for passado algum setor que não faz parte do grupo de empresa 
            $msgsAuthorize[] = 'Algum setor não faz parte do grupo de empresa.';   
        }

        
         $userEditado = User::find($this->user_id);
        if (!$userEditado) {
            $msgsAuthorize[] = 'Usuário não encontrado.';
        } elseif ($this->user()->grupo_empresar_id != $userEditado->grupo_empresar_id) {
            $msgsAuthorize[] = 'Você não pode editar permissões desse usuário.';
        }


        if(!empty($msgsAuthorize)){
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
            'setores'   => 'array'
        ];
    }
}
