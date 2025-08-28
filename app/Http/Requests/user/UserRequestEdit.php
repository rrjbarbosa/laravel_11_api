<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserRequestEdit extends FormRequest
{
    public function authorize(): bool
    {
        //-Sem permissão de admin para Editar-----------------------------------------------------------
        if(!$this->user()->admin ){ 
            throw new AuthorizationException("Sem permissão ao cadastro de Usuários");
        }
        //-Usuário único impede duplicidade-------------------------------------------------------------
        $userExistente  = User::where('id', '<>', $this->id)->where('email',$this->email)->count();
        if($userExistente >= 1){
            throw new AuthorizationException("Esse e-mail já está cadastrado");
        }           
        return true;
    }
   
    public function rules(): array
    {
        return [
            'id'                => 'required'
        ];
    }
    public function messages(){
        return [
                                         
        ];
    }
}
