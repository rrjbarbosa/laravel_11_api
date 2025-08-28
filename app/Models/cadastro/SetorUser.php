<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SetorUser extends Model
{
    use HasFactory, HasUuids;

    //protected $table = 'setor_user';

    //protected $fillable =   [   ];    //-Podem ser atribuidos em massa
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa
}
