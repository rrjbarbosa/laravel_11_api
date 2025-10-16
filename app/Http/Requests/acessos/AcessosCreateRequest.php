<?php

namespace App\Http\Requests\acessos;

use App\Models\cadastro\Acessor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AcessosCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
        }

        $inputs->email = "";

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
            'acesso'   => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'acesso.required' => 'O campo acesso é obrigatorio.'
        ];
    }
}
