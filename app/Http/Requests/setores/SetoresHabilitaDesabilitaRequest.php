<?php

namespace App\Http\Requests\setores;

use App\Models\cadastro\Setor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SetoresHabilitaDesabilitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $msgsAuthorize = [];
        $inputs = json_decode('{}');
            
        if(!$this->user()->admin){
            $msgsAuthorize[] = 'Você não tem permissão de Administrador.';    
            $this->retornaErroParaFront($msgsAuthorize, $inputs);
        }

        $setoresIds = Setor::where('grupo_empresar_id', $this->user()->grupo_empresar_id)->pluck('id')->toArray();     
        if(!in_array($this->id, $setoresIds)){
            array_push($msgsAuthorize, 'Você não tem permissão nesse Setor, ou falta selecionar um setor');
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
