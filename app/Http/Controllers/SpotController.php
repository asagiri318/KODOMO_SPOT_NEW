<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;

class SpotController extends Controller
{
    public function create()
    {
        return view('spot.create');
    }

    public function store(Request $request)
    {
        // バリデーションルールを定義
        $request->validate([
            'title' => 'required|string|max:255',
            'prefecture' => 'required|string|max:50',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'date_visited' => 'required|date',
            'child_age_range' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048', // 画像の最大サイズは2048KB (2MB)
            'spot_url' => 'nullable|url|max:255',
        ]);

        // Spot モデルのインスタンスを作成
        $spot = new Spot();

        // フォームリクエストのデータを Spot モデルにセット
        $spot->title = $request->title;
        $spot->prefecture = $request->prefecture;
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
        return redirect()->route('mypage')->with('status', 'スポットが登録されました');
    }

    public function show($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // 詳細ページを表示
        return view('spot.show', compact('spot'));
    }
}
