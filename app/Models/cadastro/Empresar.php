<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class Empresar extends Model
{
    use HasFactory,  HasUuids;

    //protected $fillable =   [   ];    //-Podem ser atribuidos em massa
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa

    
    protected $casts = [
        'property_type' => 'array',
    ];

    static function empresasPorUsuarios($user_id, $grupo_empresa) {
        $empresas = Empresar::where('empresars.grupo_empresar_id', $grupo_empresa)
                    ->where('empresars.ativo', 1)
                    ->leftJoin('empresar_user', function ($join) use ($user_id) {
                        $join->on('empresars.id', '=', 'empresar_user.empresar_id')
                            ->where('empresar_user.user_id', '=', $user_id);
                    })
                    ->select(
                        'empresars.id',
                        'empresars.nome_fantasia',
                        'empresars.cnpjCpf',
                        'empresars.cidade',
                        'empresars.bairro',
                        DB::raw('IF(empresar_user.empresar_id IS NULL, 0, 1) as ativo')
                    )
                    ->orderBy('empresars.nome_fantasia', 'ASC')
                    ->get();
        return $empresas;            
    }
}
