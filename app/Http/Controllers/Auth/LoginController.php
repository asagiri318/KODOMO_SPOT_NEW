<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログインフォームを表示する
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理を行う
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8', // パスワードの最小文字数を指定
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは少なくとも8文字で入力してください。',
        ]);

        // ログインを試みる
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('mypage')); // ログイン後のリダイレクト先
        } else {
            // 認証失敗時のエラーメッセージ
            return back()->withErrors([
                'email' => 'メールアドレスまたはパスワードが間違っています。正しい情報を入力してください。',
            ]);
        }
    }
}
