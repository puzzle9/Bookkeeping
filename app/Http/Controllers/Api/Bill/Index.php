<?php

namespace App\Http\Controllers\Api\Bill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function base(Request $request)
    {
        return self::success();
    }
}
