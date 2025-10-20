<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;


class Acessor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'acesso', 'grupo_empresar_id'];

    static function acessosPorUsuario($user_id, $grupo_empresa) {
        $permissoes = DB::table('acessors')
            ->leftJoin('acessor_user', function($join) use ($user_id) {
                $join->on('acessors.id', '=', 'acessor_user.acessor_id')
                    ->where('acessor_user.user_id', '=', $user_id);
            })
            ->where('acessors.grupo_empresar_id', '=', $grupo_empresa)
            ->select(
                'acessors.id',
                'acessors.acesso',
                DB::raw('IF(acessor_user.user_id IS NULL, 0, 1) as ativo')
            )
            ->orderBy('acessors.acesso', 'ASC')
            ->get();

        return $permissoes;
    }
}
