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

    // FIXME: 1ユーザーの情報を閲覧するメソッドなので、profile ではく show というメソッドの命名が正しいです。
    public function profile(User $user)
    {
        // FIXME: $user は既に引数で、モデルに変換されて渡されているので、下の処理は不要です。
        $user = User::findOrFail($user->id);

        // FIXME: $spots は $user->spots で取得できるため、下記の処理は不要です。
        $spots = Spot::where('user_id', $user->id)->get();
        // FIXME: $spots は $user->children で取得できるため、下記の処理は不要です。
        $children = UserChild::where('user_id', $user->id)->get();

        // $spots と $children は with を使用して事前に取得しておきましょう。
        // https://qiita.com/HuntingRathalos/items/5a7cae35a49a2795e8f2

        // FIXME: $spots と $children は $user モデルから取得可能なので view に渡すのは user だけでOKです。
        return view('profile.profile', compact('user', 'spots', 'children'));
    }
}
