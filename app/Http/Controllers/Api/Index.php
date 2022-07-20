<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function start(Request $request)
    {
        return response()->json([
            'time' => now()->toDateTimeString(),
        ]);
    }
}
