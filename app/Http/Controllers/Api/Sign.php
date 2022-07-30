<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Sign extends Controller
{
    public function in(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::select('id', 'password')->where('username', $request->input('username'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return self::failed('用户名或密码错误');
        }

        return $this->token($user);
    }

    public function reg(Request $request, User $user)
    {
        $user_table = $user->getTable();
        $request->validate([
            'username' => 'bail|required|max:30|unique:' . $user_table,
            'email'    => 'bail|required|email|max:50|unique:' . $user_table,
            'password' => 'bail|required|max:30',
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->token($user);
    }

    public function token(User $user)
    {
        return self::success([
            'username' => $user->username,
            'token'    => User::generateTokenByUser($user, 'sign'),
        ]);
    }
}
