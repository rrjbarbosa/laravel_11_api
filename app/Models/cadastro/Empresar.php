<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Empresar extends Model
{
    use HasFactory,  HasUuids;

    //protected $fillable =   [   ];    //-Podem ser atribuidos em massa
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa

    
    protected $casts = [
        'property_type' => 'array',
    ];
}
