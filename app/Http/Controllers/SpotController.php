<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Prefecture;
use App\Models\UserFavoriteSpot;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    // バリデーションルールを定義するメソッド
    private function validationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'prefecture_id' => 'required|exists:prefectures,id', // prefecture_id をバリデーションルールに追加
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'date_visited' => 'required|date',
            'child_age_range' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048', // 画像の最大サイズは2048KB (2MB)
            'spot_url' => 'nullable|url|max:255',
        ];
    }

    public function create()
    {
        $prefectures = Prefecture::all(); // 都道府県の一覧を取得

        return view('spot.create', [
            'prefectures' => $prefectures,
        ]);
    }

    public function store(Request $request)
    {
        // バリデーションルールを取得
        $rules = $this->validationRules();

        // リクエストのバリデーション
        $request->validate($rules);

        // Spot モデルのインスタンスを作成
        $spot = new Spot();

        // フォームリクエストのデータを Spot モデルにセット
        $spot->title = $request->title;
        $spot->prefecture_id = $request->prefecture_id; // prefecture_id をセット
        $spot->city = $request->city;
        $spot->description = $request->description;
        $spot->date_visited = $request->date_visited;
        $spot->child_age_range = $request->child_age_range;
        $spot->rating = $request->rating;
        $spot->spot_url = $request->spot_url;
        $spot->user_id = Auth::id(); // ログインしているユーザーのIDをセットする

        // 画像がアップロードされている場合は保存処理を行う
        if ($request->hasFile('photo')) {
            $spot->photo = $request->file('photo')->store('photos', 'public');
        }

        // Spot モデルを保存
        $spot->save();

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('mypage')->with('status', 'スポットが登録されました');
    }

    public function show($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // ログインしているユーザーがお気に入り登録しているかチェック
        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = Auth::user()->favoriteSpots()->where('spot_id', $spot->id)->exists();
        }

        // 詳細ページを表示
        return view('spot.show', compact('spot', 'isFavorited'));
    }

    public function addToFavorites($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // ログインしているか確認
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'お気に入りに登録するにはログインしてください');
        }

        // ログインしているユーザーがすでにお気に入り登録しているかチェック
        if (Auth::user()->favoriteSpots()->where('spot_id', $spot->id)->exists()) {
            return redirect()->back()->with('info', 'このスポットはすでにお気に入り登録されています');
        }

        // お気に入りに追加する
        Auth::user()->favoriteSpots()->attach($spot);

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('spot.show', $spot->id)->with('success', 'お気に入りに登録しました');
    }

    public function edit($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // 都道府県を取得
        $prefectures = Prefecture::all();

        // 編集ページを表示
        return view('spot.edit', compact('spot', 'prefectures'));
    }

    public function update(Request $request, $id)
    {
        // バリデーションルールを取得
        $rules = $this->validationRules();

        // リクエストのバリデーション
        $request->validate($rules);

        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // フォームリクエストのデータを Spot モデルにセット
        $spot->title = $request->title;
        $spot->prefecture_id = $request->prefecture_id; // prefecture_id をセット
        $spot->city = $request->city;
        $spot->description = $request->description;
        $spot->date_visited = $request->date_visited;
        $spot->child_age_range = $request->child_age_range;
        $spot->rating = $request->rating;
        $spot->spot_url = $request->spot_url;

        // 画像がアップロードされている場合は保存処理を行う
        if ($request->hasFile('photo')) {
            $spot->photo = $request->file('photo')->store('photos', 'public');
        }

        // Spot モデルを保存
        $spot->save();

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('spot.show', $spot->id)->with('status', 'スポットが更新されました');
    }

    public function destroy($id)
    {
        // IDに該当するスポットを取得して削除する
        $spot = Spot::findOrFail($id);
        $spot->delete();

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('mypage')->with('status', 'スポットが削除されました');
    }

    public function favorite()
    {
        $user = Auth::user();
        $favoriteSpots = $user->favoriteSpots()->get(); // ユーザーのお気に入りスポットを取得

        return view('spot.favorite', compact('favoriteSpots'));
    }
}
