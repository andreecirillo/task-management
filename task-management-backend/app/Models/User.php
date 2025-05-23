<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;  
use Tymon\JWTAuth\Contracts\JWTSubject;

/**  
 * @property int $id  
 * @property string $name  
 * @property string $email  
 * @property string $password
 * @property DateTime $updated_at 
 * @property DateTime $created_at
 */  
class User extends Authenticatable implements JWTSubject 
{
    // Fields permitted for mass attribution.
    protected $fillable = ['name', 'email', 'password'];
    
    // Fields that won't be serialized.
    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}
