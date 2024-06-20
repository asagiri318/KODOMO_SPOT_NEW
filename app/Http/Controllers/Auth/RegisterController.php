<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // ユーザー登録処理が完了したことを通知するためのリダイレクトとメッセージ
        if ($user) {
            return redirect()->route('login')->with('status', 'ユーザー登録が完了しました');
        } else {
            return back()->withErrors(['email' => 'ユーザー登録に失敗しました']);
        }
    }
}
