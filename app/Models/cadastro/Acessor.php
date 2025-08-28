<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Acessor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'acesso', 'grupo_empresar_id'];
}
