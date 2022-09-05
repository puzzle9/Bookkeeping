<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Info;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends Info
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'is_double' => 'boolean',
        'files'     => 'json',
    ];

    // https://learnku.com/laravel/t/60910#reply202053
    public function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function ScopeSort($query)
    {
        return $query->orderBy('sort', 'asc');
    }

    public function ScopeUserId($query, $user_id = null)
    {
        return $query->where('user_id', $user_id ?: User::getUserIdByAuth());
    }

    public function ScopeBillId($query, $bill_id = null, $name = 'book_account_id')
    {
        return $query->where($name, $bill_id ?: request()->route()->parameter('bill_id'));
    }
}
