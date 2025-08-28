<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\diversos\Funcoesr;
use Illuminate\Validation\ValidationException;

class UserRequestGridPesquisa extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Funcoesr $funcoes): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');

        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador '.$this->user()->admin);   
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
            //
        ];
    }
}
