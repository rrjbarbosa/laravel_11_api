<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;


class Permissaor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'nome', 'nome_exibicao', 'modulor_id'];

    static function permissoesPorUsuario($user_id) {
        $permissoes = DB::table('permissaors')
            ->leftJoin('acessor_permissaor', 'permissaors.id', '=', 'acessor_permissaor.permissaor_id')
            ->leftJoin('acessors', 'acessor_permissaor.acessor_id', '=', 'acessors.id')
            ->leftJoin('acessor_user', function($join) use ($user_id) {
                $join->on('acessors.id', '=', 'acessor_user.acessor_id')
                    ->where('acessor_user.user_id', '=', $user_id);
            })
            ->select(
                'permissaors.id',
                'permissaors.nome',
                'permissaors.nome_exibicao',
                DB::raw('IF(acessor_user.user_id IS NULL, 0, 1) as ativo')
            )
            ->orderBy('permissaors.nome', 'ASC')
            ->get();

        return $permissoes;
    }
}
