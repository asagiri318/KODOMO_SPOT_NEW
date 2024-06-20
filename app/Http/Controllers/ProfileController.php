<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // User モデルを正しくインポートする

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // 現在認証されているユーザーの情報を取得して、プロフィール編集用のビューを返す
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // プロフィール更新のためのバリデーションルールを定義
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // 現在認証されているユーザーを取得し、リクエストのデータで更新
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // プロフィール更新後にマイページにリダイレクトし、成功メッセージを表示
        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました');
    }

    /**
     * Delete the user's profile.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        // 現在認証されているユーザーを取得し、削除
        $user = Auth::user();
        $user->delete();

        // ユーザー削除後にログアウトし、トップページにリダイレクト
        Auth::logout();
        return redirect()->route('welcome')->with('status', 'アカウントが削除されました');
    }
}
