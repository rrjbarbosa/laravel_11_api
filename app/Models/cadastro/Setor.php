<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class Setor extends Model
{
    use HasFactory, HasUuids;

    //protected $fillable =   [   ];  //-Podem ser atribuidos em massa
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa

    static function setoresPorUsuarios($user_id, $grupo_empresa_id){
        $setores = Setor::where('setors.grupo_empresar_id', $grupo_empresa_id)
                    ->where('setors.ativo', 1)
                    ->leftJoin('setor_users', function ($join) use ($user_id) {
                        $join->on('setors.id', '=', 'setor_users.setor_id')
                            ->where('setor_users.user_id', '=', $user_id);
                    })
                    ->select(
                        'setors.id',
                        'setors.setor',
                        DB::raw('IF(setor_users.setor_id IS NULL, 0, 1) as ativo')
                    )
                    ->orderBy('setors.setor', 'ASC')
                    ->get();     
        return  $setores;           
    }
}
