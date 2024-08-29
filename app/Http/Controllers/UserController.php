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
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        // クエリと並べ替えの取得
        $query = $request->input('query');
        $sort = $request->input('sort', 'newest');

        // スポットの取得と検索
        $spots = Spot::where('user_id', $user->id);

        if ($query) {
            $keywords = explode(' ', $query);
            foreach ($keywords as $keyword) {
                $spots = $spots->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('city', 'like', "%{$keyword}%");
                });
            }
        }

        // 並べ替えの条件
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'newest':
                $spots = $spots->orderBy('date_visited', 'desc');
                break;
            case 'oldest':
                $spots = $spots->orderBy('date_visited', 'asc');
                break;
            case 'most_liked':
                $spots = $spots->withCount('likes')->orderBy('likes_count', 'desc');
                break;
        }

        // スポットと子どもの情報を取得
        $spots = $spots->get();
        $children = UserChild::where('user_id', $user->id)->get();

        return view('mypage', compact('user', 'spots', 'children'));
    }

    public function profile(User $user)
    {
        $user = User::findOrFail($user->id);
        $spots = Spot::where('user_id', $user->id)->get();
        $children = UserChild::where('user_id', $user->id)->get();

        return view('profile.profile', compact('user', 'spots', 'children'));
    }
}
