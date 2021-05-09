<?php

namespace App\Model;


use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;


//class dys_user extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
class dys_user extends Model 
{
	//..	protected $fillable = ['UserEmail', 'UserPassword'];
	// use Authenticatable, Authorizable;

 //    /**
 //     * The attributes that are mass assignable.
 //     *
 //     * @var array
 //     */
 //    protected $fillable = [
 //        'UserEmail', 'UserPassword',
 //    ];

 //    *
 //     * The attributes excluded from the model's JSON form.
 //     *
 //     * @var array
     
 //    protected $hidden = [
 //        'UserPassword',
 //    ];

 //    public function getJWTIdentifier()
 //    {
 //        return $this->getKey();
 //    }

 //    public function getJWTCustomClaims()
 //    {
 //        return [];
 //    }
    //
}
