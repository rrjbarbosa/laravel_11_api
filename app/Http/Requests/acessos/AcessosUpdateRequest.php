<?php

namespace App\Http\Requests\acessos;

use App\Models\cadastro\Acessor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AcessosUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            array_push($msgsAuthorize, 'Você não tem permissão de Administrador.');   
        }

        $acessosExistente = Acessor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)   //Acceos cadastrado por grupo de empresa diferente do acesso passado
                                   ->where('id','!=', $this->id)                 
                                   ->pluck('acesso')->toArray();        
        if (in_array($this->acesso, $acessosExistente)) {  
            $msgsAuthorize[] = 'O acesso já Existe';   
            $inputs->acesso = "";
        }

        $acessosGrupoEmpresa = Acessor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();
        if (!in_array($this->id, $acessosGrupoEmpresa)) { //- Se não for passado o acesso, ou se o acesso não fizer parte do grupo de empresa 
            $msgsAuthorize[] = 'O acesso não faz parte do seu grupo de empresa.';   
            $inputs->acesso = "";
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
            'acesso'    => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'acesso.required' => 'O campo acesso é obrigatorio.'
        ];
    }
}
