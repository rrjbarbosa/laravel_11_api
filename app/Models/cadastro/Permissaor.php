<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;



class Permissaor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'nome', 'nome_exibicao', 'modulor_id'];
}
