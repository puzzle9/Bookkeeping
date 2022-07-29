<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;

use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasApiTokens;

    public static function generateTokenByUser(self $user, string $token_name, $abilities = ['*'])
    {
        return $user->createToken($token_name, $abilities)->plainTextToken;
    }

    public static function getUserIdByAuth()
    {
        return Auth::id();
    }
}
