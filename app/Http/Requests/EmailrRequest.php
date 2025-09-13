<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Emailr;
use App\Models\User;
use App\Models\diversos\Funcoesr;

class EmailrRequest extends FormRequest
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
        $user              = auth()->user();
        $permissoesDoUser  = $user->arrayPermissaoUserNome($user->id);
        $emailsCadastrados = Emailr::where('empresar_id', $this->empresar_id)->pluck('email')->toarray();
        $emailsCadastrados = strtoupper(json_encode($emailsCadastrados));     //-strtoupper Transforma o json em maiúsculo
        $emailsCadastrados = json_decode($emailsCadastrados);                 //-Retorna para array
        
        if($this->acao != "HABILITA_DESABILITA" && in_array(strtoupper($this->email), $emailsCadastrados)){ return [ 'emailJaCadastrado' => 'required' ]; }

        switch ($this->acao) {
            case "CRIAR": //==============================================================
                if(!in_array('cad_email_criar', $permissoesDoUser)){ return [ 'semPemissaoCriar' => 'required' ]; }
                return [
                    'email'           => 'required|email',
                    'empresar_id'     => 'required'
                ];break; 
            case "ATUALIZAR": //=============================================================
                if(!in_array('cad_email_editar', $permissoesDoUser)){ return [ 'SemPemissaoEditar' => 'required' ]; } 
                if($this->ativo == 0){ return [ 'desabilitadoEdicaoBloqueada' => 'required' ]; } 
                return [
                    'id'              => 'required',
                    'email'           => 'required|email'                        
                ];break; 
            case "HABILITA_DESABILITA": //=============================================================
                if(!in_array('cad_email_deletar', $permissoesDoUser)){ return [ 'SemPemissaoHabilitarDesabilitar' => 'required' ]; }
                return [
                    'id'              => 'required',
                    'ativo'           => 'required'                      
                ];break;     
        }
    }
    public function messages(){
        return [
            'emailJaCadastrado.required'                => 'Email já Cadastrado',
            'semPemissaoCriar.required'                 => 'Sem permissão para criar ',
            'SemPemissaoEditar.required'                => 'Sem permissão para editar ',
            'SemPemissaoHabilitarDesabilitar.required'  => 'Sem permissão para Habilitar / Desabilitar ',
            'desabilitadoEdicaoBloqueada.required'      => 'Desabilitado Edição Bloqueada'
        ];
    }
}
