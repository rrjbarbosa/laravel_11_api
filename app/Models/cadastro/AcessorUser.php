<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AcessorUser extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'acessor_user';

    //protected $fillable =   [   ];    //-Podem ser atribuidos em massa
    protected $guarded = ['id', 'grupo_empresar_id', 'acessor_id', 'user_id'];      //-Não podem ser atribuidos em massa
}
