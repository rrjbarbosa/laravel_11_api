<?php

namespace App\Http\Requests\permissao;

use App\Models\cadastro\Acessor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class permissoesParaAcessoRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
        }

        if(!$this->acessor_id){
            array_push($msgsAuthorize, 'Acesso é obrigatório');   
        }

        $acessoEditado = Acessor::find($this->acessor_id);
        if($this->user()->grupo_empresar_id != $acessoEditado->grupo_empresar_id){
            array_push($msgsAuthorize, 'Você não pode editar o acesso desse grupo de empresa');
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
            'acessor_id'   => 'string|required'
        ];
    }
}
