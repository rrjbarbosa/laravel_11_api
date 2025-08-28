<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmpresarRequest extends FormRequest
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
        
        
        switch($this->acao){
            case 'ATIVAR' || 'DESATIVAR':
                return [
                    'empresar_id'       => 'required'
                ];
            case 'PERMICOES':    
                return [
                    'user_id'           => 'required',
                    'empresar_id'       => 'required'
                ];
        }
        
        return [
            'acao'              => 'required'            
        ];
    }
    public function messages(){
        return [
            'semPermissaoAdmin.required' => 'Sem permissão ao Cadastro de Usuários',                             
            'acao.required'              => 'Ação Obrigatória',  
            'empresar_id.required'       => 'Falta Empresa', 

        ];
    }
}
