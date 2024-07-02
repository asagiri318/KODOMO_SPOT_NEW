<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'nullable|date', // 追加: 生年月日のバリデーション
            'address' => 'nullable|string|max:255', // 追加: 都道府県・市区町村のバリデーション
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate, // 追加: 生年月日を保存
            'address' => $request->address,     // 追加: 都道府県・市区町村を保存
        ]);

        Auth::login($user);

        return redirect()->route('mypage')->with('success', '登録が完了しました');
    }
}
