<?php

namespace App\Models\cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Modulor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'modulo'];
}
