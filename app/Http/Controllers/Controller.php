<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public static function failed($message)
    {
        return abort(422, $message);
    }

    public static function success($data = [], $message = 'success')
    {
        return response()->json([
            'data'    => $data,
            'message' => $message,
        ]);
    }
}
