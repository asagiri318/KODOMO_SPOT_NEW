<?php

namespace App\Http\Controllers;

use App\Models\Prefecture;
use App\Models\UserChild;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $prefectures = Prefecture::all();
        $children = $user->childrenAges->map(function ($child) {
            return [
                'id' => $child->id,
                'birthdate' => Carbon::parse($child->birthdate)->format('Y-m-d'), // 生年月日をフォーマットして取得
            ];
        });

        return view('profile.edit', compact('user', 'prefectures', 'children'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|image|max:2048',
            'prefecture_id' => 'required|exists:prefectures,id',
            'city' => 'nullable|string|max:255',
            'children_birthdates.*' => 'nullable|date_format:Y-m-d', // 子供の生年月日は配列で受け取る
        ]);

        $user = Auth::user();
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->prefecture_id = $request->prefecture_id;
        $user->city = $request->city;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // 子供の生年月日を更新
        if ($request->has('children_birthdates')) {
            $childrenBirthdates = $request->children_birthdates;
            UserChild::where('user_id', $user->id)->delete(); // 一旦古い情報を削除

            foreach ($childrenBirthdates as $birthdate) {
                $child = new UserChild();
                $child->user_id = $user->id;

                // データが正しい日付形式かチェック
                if (strtotime($birthdate)) {
                    $child->birthdate = Carbon::createFromFormat('Y-m-d', $birthdate); // Carbon インスタンスに変換
                    $child->save();
                } else {
                    // 正しい日付形式でない場合はエラー処理を行うか、スキップする
                    continue;
                }
            }
        }

        $user->save();

        return redirect()->route('mypage')->with('status', 'プロフィールが更新されました');
    }
}
