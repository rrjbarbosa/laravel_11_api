<?php

namespace App\Http\Requests\setores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class SetoresEmUserUpdateGridPesquisaRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            $msgsAuthorize[] = 'Você não tem permissão de Administrador.';    
        }
        
        $userPesquisado = User::find($this->user_id);
        if (!$userPesquisado) {
            $msgsAuthorize[] = 'Usuário não encontrado.';
        } elseif ($this->user()->grupo_empresar_id != $userPesquisado->grupo_empresar_id) {
            $msgsAuthorize[] = 'Você não pode pesquisar esse usuário.';
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
            'user_id'   => 'required',
            'setor'     => 'string'
        ];
    }
}
