<?php

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\BookAccount;

class Account extends Controller
{
    public function list(Request $request)
    {
        // todo: 分页
        $data = BookAccount::UserId()->select('id', 'name', 'remark', 'updated_at')->latest()->get();

        return self::success($data);
    }

    public function createOrUpdate(Request $request)
    {
        $request->validate([
            'id'     => 'nullable|integer',
            'name'   => 'required|string|max:10',
            'remark' => 'nullable|string|max:200',
        ]);

        // todo: 横向越权
        BookAccount::updateOrCreate([
            'id'      => $request->input('id'),
            'user_id' => User::getUserIdByAuth(),
        ], [
            'name'   => $request->input('name'),
            'remark' => $request->input('remark'),
        ]);

        return self::success();
    }

    public function delete(Request $request)
    {
        BookAccount::UserId()->where('id', $request->input('id'))->delete();
    }
}
