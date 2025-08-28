<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EstoquerUser extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'estoquer_user';  

    protected $fillable = ['id','estoquer_id', 'user_id', 'empresar_id', 'grupo_empresar_id' ];
}
