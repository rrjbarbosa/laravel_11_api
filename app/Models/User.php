<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [              //-Podem ser atribuidos em massa
        'ativo', 'name', 'email', 'password', 'email_envio_msg','grupo_empresar_id'
     ];

    // protected $guarded = ['id'];      //-Não podem ser atribuidos em massa

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //public $incrementing = false;     //Ativo no laravel 9
    public function setPasswordAttribute($value)                               #--- Mutatores modifica valores antes de inserir/atualizar no banco
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    public function arrayPermissaoUserNome($idUser){
        $permissoes = DB::table('permissaors')
                            ->join('acessor_permissaor','permissaors.id', '=', 'acessor_permissaor.permissaor_id')    
                            ->join('acessor_user','acessor_permissaor.acessor_id', '=', 'acessor_user.acessor_id')
                            ->where('acessor_user.user_id', '=', $idUser)
                            ->select(['permissaors.nome'])
                            ->pluck('nome')->toarray();
        return $permissoes;                        
    } 
    public function arrayPermissaoUserId($idUser){
        $permissoes = DB::table('acessor_permissaor')
                            ->join('acessor_user','acessor_permissaor.acessor_id', '=', 'acessor_user.acessor_id')
                            ->where('acessor_user.user_id', '=', $idUser)
                            ->select(['acessor_permissaor.permissaor_id'])
                            ->pluck('permissaor_id')->toarray();
        return $permissoes;                        
    }   
    public function senhaValida($senha) {
        return preg_match('/[a-z]/', $senha)        // tem pelo menos uma letra minúscula
         && preg_match('/[A-Z]/', $senha)           // tem pelo menos uma letra maiúscula
         && preg_match('/[0-9]/', $senha)           // tem pelo menos um número
         && preg_match('/^[\w$@]{6,}$/', $senha);   // tem 6 ou mais caracteres
    }
}
