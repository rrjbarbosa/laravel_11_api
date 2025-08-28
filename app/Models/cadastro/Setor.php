<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setor extends Model
{
    use HasFactory, HasUuids;

    //protected $fillable =   [   ];  //-Podem ser atribuidos em massa
    protected $guarded = ['id'];      //-Não podem ser atribuidos em massa

    protected $appends = ['css'];

    public function getCssAttribute()
    {
        return $this->ativo == 1 ? 'ativo' : 'inativo';
    }

  
}
