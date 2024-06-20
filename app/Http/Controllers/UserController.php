<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // 現在ログインしているユーザー情報を取得
        $user = Auth::user();

        if (!$user) {
            abort(404, 'User not found');
        }

        // ユーザーが持つスポットのリストを取得
        $spots = $user->spots;

        return view('mypage', compact('user', 'spots'));
    }
}
