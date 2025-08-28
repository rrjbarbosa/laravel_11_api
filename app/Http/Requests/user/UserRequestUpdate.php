<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use App\Models\diversos\Funcoesr;
use App\Models\User;

class UserRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Funcoesr $funcoes): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
        }

        $userExistente  = User::where('id', '<>', $this->id)->where('email',$this->email)->count();
        if($userExistente >= 1){
           array_push($msgsAuthorize, 'No campo (E-mail)  Esse e-mail já está cadastrado.');
           $inputs->email = "";
        }

        $usuario = User::find($this->id);
        if($this->user()->grupo_empresar_id != $usuario->grupo_empresar_id){
            array_push($msgsAuthorize, 'Você não tem permissão a esse grupo de empresa.');   
        }

        if(count($msgsAuthorize) > 0){
            throw ValidationException::withMessages([
                'msgsAuthorize' => $msgsAuthorize,
                'inputs'        => $inputs,
            ]);
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required',
            'email'             => 'required|email',
            'email_envio_msg'   => 'required|email',
        ];
    }
}
