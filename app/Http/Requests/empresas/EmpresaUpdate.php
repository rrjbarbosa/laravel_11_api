<?php

namespace App\Http\Requests\empresas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Models\cadastro\Empresar;
use Illuminate\Validation\Rule;

class EmpresaUpdate extends FormRequest
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
            'imgParaUpload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', //max:10240 máximo de 10MB 
            'razao_social'  => 'required|string',
            'nome_fantasia' => 'required|string',
            'cnpjCpf' => [
                'required',
                Rule::when(fn ($input) => $input->cnpjOuCpf === 'cpf',  ['digits:11']),
                Rule::when(fn ($input) => $input->cnpjOuCpf === 'cnpj', ['digits:14']),
            ],
        ];
    }

    public function messages()
    {
        return [
            'imgParaUpload.image'       => 'O arquivo deve ser uma imagem válida',
            'imgParaUpload.mimes'       => 'A imagem deve ser JPEG, PNG, JPG, GIF ou WEBP',
            'imgParaUpload.max'         => 'A imagem não pode ser maior que 2MB',
            'nome_fantasia.required'    => 'O nome fantasia é obrigatório',
            'razao_social.required'     => 'A razaão Social é obrigatório',
            'cnpjCpf.required'          => 'O CNPJ / CPF é obrigatório',
            'cnpjCpf.digits'            => 'Se for CPF obrigatório 11 Dígitos, e CNPJ 14 Díditos',
        ];
    }
}
