<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\cadastro\Estoquer;
use App\Models\User;

class EstoquerRequest extends FormRequest
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
        $user                = auth()->user();
        $permissoesDoUser    = $user->arrayPermissaoUserNome($user->id);
        $estoquesCadastrados = Estoquer::where('empresar_id', $this->empresar_id)->pluck('estoque')->toarray();
        $estoquesCadastrados = strtoupper(json_encode($estoquesCadastrados));     //-strtoupper Transforma o json em maiúsculo
        $estoquesCadastrados = json_decode($estoquesCadastrados);                 //-Retorna para array
                  
        if($this->acao != "HABILITA_DESABILITA" && in_array(strtoupper($this->estoque), $estoquesCadastrados)){ return [ 'estoqueJaCadastrado' => 'required' ]; }

        switch ($this->acao) {
            case "CRIAR": //==============================================================
                if(!in_array('cad_estoque_criar', $permissoesDoUser)){ return [ 'SemPemissaoCriar' => 'required' ]; }
                return [
                    'estoque'         => 'required',
                    'empresar_id'     => 'required'
                ];break; 
            case "ATUALIZAR": //=============================================================
                if(!in_array('cad_estoque_editar', $permissoesDoUser)){ return [ 'SemPemissaoEditar' => 'required' ]; } 
                if($this->ativo == 0){ return [ 'desabilitadoEdicaoBloqueada' => 'required' ]; } 
                return [
                    'id'              => 'required',
                    'estoque'         => 'required'                        
                ];break; 
            case "HABILITA_DESABILITA": //=============================================================
                if(!in_array('cad_estoque_deletar', $permissoesDoUser)){ return [ 'semPemissaoHabilitarDesabilitar' => 'required' ]; }
                return [
                    'id'              => 'required',
                    'ativo'           => 'required'                      
                ];break;     
        }    
    }
    public function messages(){
        return [
            'estoqueJaCadastrado.required'              => 'Estoque já Cadastrado',
            'SemPemissaoCriar.required'                 => 'Sem permissão para criar ',
            'SemPemissaoEditar.required'                => 'Sem permissão para editar ',
            'semPemissaoHabilitarDesabilitar.required'  => 'Sem permissão para Habilitar / Desabilitar ',
            'desabilitadoEdicaoBloqueada.required'      => 'Desabilitado Edição Bloqueada'
        ];
    }
}
