<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Info;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends Info
{
    use SoftDeletes;

    protected $fillable = [];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'is_double' => 'boolean',
        'files'     => 'json',
    ];
}
