<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function start(Request $request)
    {
        return view('web.index', [
            'title'       => config('app.name'),
            'description' => '记账',
        ]);
    }
}