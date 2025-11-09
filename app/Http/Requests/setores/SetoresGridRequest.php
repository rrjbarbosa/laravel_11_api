<?php

namespace App\Http\Requests\setores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SetoresGridRequest extends FormRequest
{
    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            $msgsAuthorize[] = 'Você não tem permissão de Administrador.';    
            $this->retornaErroParaFront($msgsAuthorize, $inputs);
        }
               
        return true;
    }

    public function retornaErroParaFront($msgsAuthorize, $inputs){
        throw ValidationException::withMessages([
                'msgsAuthorize' => $msgsAuthorize,
                'inputs'        => $inputs,
            ]);
    }

    public function rules(): array
    {
        return [
            
        ];
    }
}
