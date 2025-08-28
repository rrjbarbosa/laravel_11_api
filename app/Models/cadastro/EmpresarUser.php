<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmpresarUser extends Model
{
    use HasFactory, HasUuids;

    //protected $table = 'empresar_user';
    //protected $fillable = ['id', 'company_id', 'group_company_id', 'user_id' ];

    //protected $fillable =   [   ];    //-Podem ser atribuidos em massa
    protected $table = 'empresar_user';
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa
}
