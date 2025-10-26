<?php

namespace App\Http\Requests\acessoUser;

use Illuminate\Foundation\Http\FormRequest;

class AcessorUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        $user = auth()->user();
        if($user->admin != 1 || !$this->acao){ return [ 'semPermissaoAdmin' => 'required' ]; }
        
        return [
            'acao' => 'required'            
        ];
    }
    public function messages(){
        return [
            'semPermissaoAdmin.required' => 'Sem permissão ao Cadastro de Usuários'
        ];
    }
}
