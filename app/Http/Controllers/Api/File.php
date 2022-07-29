<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class File extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $directory = implode('/', [
            'files',
            User::getUserIdByAuth(),
            now()->format('Y/m/d'),
        ]);
        $path = Storage::putFile($directory, $request->file('file'));

        return self::success([
            'path' => $path,
            'url'  => Storage::url($path),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required',
        ]);

        $path = $request->input('path');

        if (explode('/', $path)[1] != User::getUserIdByAuth()) {
            return self::failed('这么做可不对哦');
        }

        // todo: 删除文件 后 又返回等相关逻辑
        return self::success();
    }
}
