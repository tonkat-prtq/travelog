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
        $user = User::where('name', $name)->first()
            ->load([
                'articles.user',
                'articles.likes',
                'articles.tags',
                'articles.photos'
            ]);

        $articles = $user->articles->sortByDesc('created_at');

        return view('users.show', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function likes(string $name)
    {
        $user = User::where('name', $name)->first()
            ->load([
                // いいねした記事を投稿したユーザー
                'likes.user',
                // いいねした記事を、いいねした他のユーザーモデル
                'likes.likes',
                // いいねした記事につけられたタグ
                'likes.tags'
            ]);

        $articles = $user->likes->sortByDesc('created_at');

        return view('users.likes',[
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function followings(string $name)
    {
        $user = User::where('name', $name)->first()
            // フォロー中のユーザーのフォロワーを取得
            ->load('followings.followers');

        $followings = $user->followings->sortByDesc('created_at');

        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }
    
    public function followers(string $name)
    {
        $user = User::where('name', $name)->first()
        // フォロワーのフォロワーを取得
            ->load('followers.followers');

        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    public function follow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $name];
    }

    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }
}
