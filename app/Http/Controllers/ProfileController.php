<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Requests\StoreProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $prefectures = config('prefectures');
        $children = $user->children->map(function ($child) {
            return [
                'id' => $child->id,
                'birthdate' => Carbon::parse($child->birthdate)->format('Y-m-d'),
            ];
        });

        return view('profile.edit', compact('user', 'prefectures', 'children'));
    }

    public function update(StoreProfileRequest $request)
    {
        // バリデーションは自動的に StoreProfileRequest 内で行われる

        $user = Auth::user();
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->prefecture = $request->prefecture;
        $user->city = $request->city;
        $user->introduction = $request->introduction;

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
            UserChild::where('user_id', $user->id)->delete();

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
}
