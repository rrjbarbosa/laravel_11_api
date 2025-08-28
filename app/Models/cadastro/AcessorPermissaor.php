<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class AcessorPermissaor extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'acessor_permissaor';

    protected $fillable = ['id', 'permissao_grupor_id', 'permissaor_id', 'modelor_id', 'grupo_empresar_id'];
}
