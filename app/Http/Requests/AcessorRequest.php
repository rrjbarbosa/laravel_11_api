<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\AcessorUser;
use App\Models\User;

class AcessorRequest extends FormRequest
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
        if($user->admin != 1 || !$this->acao){ 
            return [ 'semPermissaoAdmin' => 'required' ]; 
        }
        if($this->acao == 'EXCLUIR'){ 
            return [
                'id' => 'required'      
            ];
        }
        elseif($this->acao == 'CRIAR'){
            return [
                'acesso' => 'required'            
            ];
        }
        elseif($this->acao == 'editar'){
            return [
                'id' => 'required',       
                'acesso' => 'required'            
            ];
        }
        
        return [
            'acao' => 'required'            
        ];
    }
    public function messages(){
        return [
            'semPermissaoAdmin.required' => 'Sem permissão ao Cadastro de Usuários',
            'acesso.required'            => 'ERRO... O Acesso está vazio'
        ];
    }
}
