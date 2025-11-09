<?php

namespace App\Http\Requests\empresas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\cadastro\Empresar;
use Illuminate\Validation\Rule;

class EmpresaDesabilitaRemoveVinculoComUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
            $this->retornaErroParaFront($msgsAuthorize, $inputs);
        }

        $empresasIds = Empresar::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();     
        if(!in_array($this->id, $empresasIds)){
            array_push($msgsAuthorize, 'Você não tem permissão nessa empresa, ou falta selecionar uma empressa');
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

    public function messages()
    {
        return [
            
        ];
    }
}
