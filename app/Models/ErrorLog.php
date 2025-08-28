<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class ErrorLog extends Model{

    protected $fillable = ['id', 'user_id', 'username','mensagem', 'arquivo', 'linha'];

    /**
     * @param User $user
     * @param \Exception $e
     */
    public function __construct($user , $e){
        $this->user_id   = $user->id;
        $this->username  = $user->name;
        $this->mensagem  = $e->getMessage();
        $this->arquivo   = $e->getFile();
        $this->linha     = $e->getLine();
        $this->save();
    }
}