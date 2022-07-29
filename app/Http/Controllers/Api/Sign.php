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
        $request->validate([
            'username' => 'required|max:30|unique:' . $user->getTable(),
            'password' => 'required|max:30',
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->token($user);
    }

    public function token(User $user)
    {
        return self::success([
            'token' => User::generateTokenByUser($user, 'sign'),
        ]);
    }
}
