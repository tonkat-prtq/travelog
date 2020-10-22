<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name)
    {
        // $nameにはルーティングの{name}に入った文字列が渡ってくる
        // Userのnameカラムが、$nameと一致するユーザーを取ってくる
        $user = User::where('name', $name)->first();

        return view('users.show', [
            'user' => $user,
        ]);
    }
}
