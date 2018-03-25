<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use \App\Models\BlogComments;

class Users extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    protected $table = 'users';
    protected $fillable = ['name', 'slug', 'about', 'email', 'password', 'toasts', 'active', 'password_raw'];

    public function scopeOnlyActive($query) {
        return $query->where('active', '1');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims()  {
        return [];
    }

    public function comments() {
        return $this->hasMany(BlogComments::class, 'user_id', 'id')->where('active', '1')->orderBy('id', 'DESC');
    }

}
