<?php

namespace App\Domain\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes; // toggle soft deletes
    protected $table = 'users';
    protected $fillable = ['email', 'password']; // for mass creation
    protected $hidden = ['password', 'deleted_at']; // hidden columns from select results
    protected $dates = ['deleted_at']; // the attributes that should be mutated to dates

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = password_hash($pass, PASSWORD_BCRYPT);
    }
}
