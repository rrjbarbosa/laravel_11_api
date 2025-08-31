<?php

namespace App\Http\Requests\empresas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\diversos\Funcoesr;
use App\Models\cadastro\Empresar;


class EmpresaEmUserUpdateHabilitaDesabilitaRequest extends FormRequest
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

        $empresa = Empresar::find($this->id);
        if($this->user()->grupo_empresar_id != $empresa->grupo_empresar_id){
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

    public function rules(): array
    {
        return [
            'id'        => 'required',
            'ativo'     => 'required',
            'user_id'   => 'required'
        ];
    }
}
