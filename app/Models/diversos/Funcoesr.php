<?php

namespace App\Models\diversos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;                                     //-Para Gerar UUID
use Illuminate\Support\Facades\DB;

class Funcoesr extends Model
{
    use HasFactory;

    public function gerarUuid(){
        return Str::uuid();
    }

    public function arrayPermissaoUserNome($idUser){
        $permissoes = DB::table('permissaors')
                            ->join('acessor_permissaor','permissaors.id', '=', 'acessor_permissaor.permissaor_id')    
                            ->join('acessor_user','acessor_permissaor.acessor_id', '=', 'acessor_user.acessor_id')
                            ->where('acessor_user.user_id', '=', $idUser)
                            ->select(['permissaors.nome'])
                            ->pluck('nome')->toarray();
        return $permissoes;                        
    }    
    
    public function arrayPermissaoUserId($idUser){
        $permissoes = DB::table('acessor_permissaor')
                            ->join('acessor_user','acessor_permissaor.acessor_id', '=', 'acessor_user.acessor_id')
                            ->where('acessor_user.user_id', '=', $idUser)
                            ->select(['acessor_permissaor.permissaor_id'])
                            ->pluck('permissaor_id')->toarray();
        return $permissoes;                        
    }

    public function senhaValida($senha) {
        return preg_match('/[a-z]/', $senha)        // tem pelo menos uma letra minúscula
         && preg_match('/[A-Z]/', $senha)           // tem pelo menos uma letra maiúscula
         && preg_match('/[0-9]/', $senha)           // tem pelo menos um número
         && preg_match('/^[\w$@]{6,}$/', $senha);   // tem 6 ou mais caracteres
    }

    function paginacao_paginas($dados){                                    #--- Mutatores modifica valores antes de inserir/atualizar no banco
        $dados = $dados;
        //==========================================INÍCIO PAGINAÇÃO===============================================
        $paginar[] = false;
        unset($paginar[0]);
        //-------------------------------------------------------------------------------ANTES DA PÁGINA SELECIONADA
        $paginaAtual = $dados->currentPage();
        $ultimaPagina = $dados->lastPage();
        $tresAntes = $paginaAtual-3 ;
        while ($tresAntes < $paginaAtual) {
            if($tresAntes > 1){array_push($paginar, $tresAntes);}
            $tresAntes++;
        }
        //-------------------------------------------------------------------------------ATUAL PÁGINA SELECIONADA
        if($paginaAtual >= 1){array_push($paginar, $paginaAtual);}
        //-------------------------------------------------------------------------------APÓS PÁGINA SELECIONADA
        $tresDepois = $paginaAtual+3;
        while ($tresDepois > $paginaAtual) {
            if($tresDepois <= $ultimaPagina){array_push($paginar, $tresDepois);}
            $tresDepois--;
        }
        //-------------------------------------------------------------------------------PAGINAÇÃO EM ORDEM CRESCENTE
        sort($paginar);
        //============================================FIM PAGINAÇÃO=================================================

        return  $paginar;
    }
    function helpDataYmdEn($data){                                       //--Data padrão do banco de dados 000-00-00
        $dtArray = explode("/",  $data);
        $dtFormatada = $dtArray[2].'-'.$dtArray[1].'-'.$dtArray[0];
        return $dtFormatada;
    }

    function helpDatadmYBr($data){                                      //--Data padrão brasil 00/00/0000
        $data = explode("-",  $data);
        $data = $data[2].'/'.$data[1].'/'.$data[0];
        return $data;
    }

    function helpHrMn($hora){                                           //--Hora e minuto 00:00
        $data = explode(":", $hora);
        $data = $data[0].':'.$data[1];
        return $data;
    }
}
