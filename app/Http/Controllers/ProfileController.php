<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserChild;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $prefectures = Prefecture::all();
        $children = $user->children->map(function ($child) {
            return [
                'id' => $child->id,
                'birthdate' => Carbon::parse($child->birthdate)->format('Y-m-d'),
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
            'children_birthdates.*' => 'nullable|date_format:Y-m-d',
            'introduction' => 'nullable|string|max:1000', // introduction のバリデーションルールを追加
        ]);

        $user = Auth::user();
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->prefecture_id = $request->prefecture_id;
        $user->city = $request->city;
        $user->introduction = $request->introduction; // introduction の更新を追加

        // プロフィール写真の更新処理
        if ($request->hasFile('profile_photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $file = $request->file('profile_photo');
            $filename = $user->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $user->photo = $path;
        }

        $user->save();

        // 子供の生年月日を更新
        if ($request->has('children_birthdates')) {
            $childrenBirthdates = $request->children_birthdates;
            UserChild::where('user_id', $user->id)->delete(); // 一旦古い情報を削除

            foreach ($childrenBirthdates as $birthdate) {
                if (strtotime($birthdate)) {
                    $child = new UserChild();
                    $child->user_id = $user->id;
                    $child->birthdate = Carbon::createFromFormat('Y-m-d', $birthdate);
                    $child->save();
                }
            }
        }

        return redirect()->route('mypage')->with('status', 'プロフィールが更新されました');
    }

    public function deletePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('status', 'プロフィール写真が削除されました');
    }

    public function showMypage()
    {
        $user = Auth::user();
        $spots = $user->spots()->get(); // 関連するリレーションを使ってスポットを取得
        $children = $user->children()->get(); // 関連するリレーションを使って子供情報を取得

        return view('mypage', compact('user', 'spots', 'children'));
    }
}
