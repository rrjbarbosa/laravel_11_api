<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Setor;
use App\Models\User;

class SetorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user               = auth()->user();
        $permissoesDoUser   = $user->arrayPermissaoUserNome($user->id);
        $setoresCadastrados = Setor::where('grupo_empresar_id','=', $user->grupo_empresar_id)->pluck('setor')->toarray();
        $setoresCadastrados = strtoupper(json_encode($setoresCadastrados));     //-strtoupper Transforma o json em maiúsculo
        $setoresCadastrados = json_decode($setoresCadastrados);                 //-Retorna para array
        
        if($this->acao != "HABILITA_DESABILITA" && in_array(strtoupper($this->setor), $setoresCadastrados)){ return [ 'setorJaCadastrado' => 'required' ]; }

        switch ($this->acao) {
            case "CRIAR": //==============================================================
                if(!in_array('cad_setor_criar', $permissoesDoUser)){ return [ 'SemPemissaoCriar' => 'required' ]; }
                return [
                    'setor'           => 'required',
                    'empresar_id'     => 'required'
                ];break; 
            case "ATUALIZAR": //=============================================================
                if(!in_array('cad_setor_editar', $permissoesDoUser)){ return [ 'SemPemissaoEditar' => 'required' ]; } 
                if($this->ativo == 0){ return [ 'desabilitadoEdicaoBloqueada' => 'required' ]; } 
                return [
                    'id'              => 'required',
                    'setor'           => 'required'                        
                ];break; 
            case "HABILITA_DESABILITA": //=============================================================
                if(!in_array('cad_setor_deletar', $permissoesDoUser)){ return [ 'SemPemissaoHabilitarDesabilitar' => 'required' ]; }
                return [
                    'id'              => 'required',
                    'ativo'           => 'required'                      
                ];break;     
        }        
    }
    public function messages(){
        return [
            'setorJaCadastrado.required'                => 'Setor já Cadastrado',
            'SemPemissaoCriar.required'                 => 'Sem permissão para criar ',
            'SemPemissaoEditar.required'                => 'Sem permissão para editar ',
            'SemPemissaoHabilitarDesabilitar.required'  => 'Sem permissão para Habilitar / Desabilitar ',
            'desabilitadoEdicaoBloqueada.required'      => 'Desabilitado Edição Bloqueada'
        ];
    }
}
