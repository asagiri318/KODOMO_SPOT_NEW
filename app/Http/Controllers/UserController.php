<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserChild;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Spot;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $spots = Spot::where('user_id', $user->id)->get();
        $children = UserChild::where('user_id', $user->id)->get();

        return view('mypage', compact('user', 'spots', 'children'));
    }

    public function edit()
    {
        $user = Auth::user();
        $prefectures = Prefecture::all();
        $cities = City::all();

        // ユーザーの生年月日を適切なフォーマットで取得
        $user->birthdate = optional($user->birthdate)->format('Y-m-d');

        return view('profile.edit', compact('user', 'prefectures', 'cities'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'prefecture' => 'required|string',
            'city' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date_format:Y-m-d',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 追加: プロフィール写真のバリデーション
            'children_birthdates.*' => 'nullable|date_format:Y-m-d',
            'introduction' => 'nullable|string|max:1000',
        ]);

        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        $user->prefecture = $request->input('prefecture_id');
        $user->city = $request->input('city');
        $user->birthdate = $request->input('birthdate');
        $user->introduction = $request->input('introduction');

        // プロフィール写真の更新処理
        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->photo = $imagePath;
        }

        $user->save();

        // 子供の生年月日情報の更新または追加
        $birthdates = $request->input('children_birthdates', []);
        UserChild::where('user_id', $user->id)->delete(); // 古いデータを削除
        foreach ($birthdates as $birthdate) {
            if ($birthdate !== null) {
                UserChild::create([
                    'user_id' => $user->id,
                    'birthdate' => $birthdate,
                ]);
            }
        }

        return redirect()->route('mypage')->with('status', 'プロフィールが更新されました');
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $spots = Spot::where('user_id', $id)->get();
        $children = UserChild::where('user_id', $id)->get();

        return view('profile.profile', compact('user', 'spots', 'children'));
    }
}
