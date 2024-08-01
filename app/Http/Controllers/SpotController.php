<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\SpotPhoto;
use App\Models\Prefecture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Like;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class SpotController extends Controller
{
    private function resizeImage($photo, $width, $height)
    {
        // Imagick ドライバーを指定して ImageManager のインスタンスを作成
        $manager = new ImageManager(new Driver());

        $image = $manager->read($photo->getPathname());
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        return $image;
    }

    // バリデーションルールを定義するメソッド
    private function validationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'prefecture' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'date_visited' => 'required|date',
            'child_age_range' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'photos.*' => 'nullable|image|max:2048', // 画像の最大サイズ2048KB (2MB)
            'spot_url' => 'nullable|url|max:255',
        ];
    }

    public function create()
    {
        $prefectures = config('prefectures'); // config ファイルから都道府県の一覧を取得

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
        $spot->prefecture = $request->prefecture;
        $spot->city = $request->city;
        $spot->description = $request->description;
        $spot->date_visited = $request->date_visited;
        $spot->child_age_range = $request->child_age_range;
        $spot->rating = $request->rating;
        $spot->spot_url = $request->spot_url;
        $spot->user_id = Auth::id(); // ログインしているユーザーのIDをセットする

        // Spot モデルを保存
        $spot->save();

        // スポット写真の更新処理
        if ($request->hasFile('photos')) {
            $photoCounter = 1; // 画像カウンタを初期化
            foreach ($request->file('photos') as $photo) {
                // 画像をリサイズ
                $resizedImage = $this->resizeImage($photo, 700, 400);

                // ファイル名を生成
                $filename = $spot->id .  '_' . $photoCounter . $photo->getClientOriginalExtension();

                // リサイズした画像を指定のパスに保存する
                $resizedImage->save(storage_path('app/public/photos/' . $filename));

                // ファイルを指定のパスに保存する（publicディスクを使用）
                $path = $photo->storeAs('public/photos', $filename);

                // Storage::url を使用して画像のパスを取得
                $photoUrl = Storage::url('photos/' . $filename);

                // SpotPhoto モデルを作成して保存
                $spotPhoto = new SpotPhoto();
                $spotPhoto->spot_id = $spot->id;
                $spotPhoto->photo_path = $photoUrl; // パスを保存
                $spotPhoto->save();

                $photoCounter++; // 画像カウンタをインクリメント
            }
        }

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('mypage')->with('status', 'スポットが登録されました');
    }

    public function show($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // ユーザーがお気に入り登録しているかどうかをチェック
        $isFavorited = Auth::check() && Auth::user()->favoriteSpots()->where('spot_id', $spot->id)->exists();

        $spot = Spot::with('photos')->findOrFail($id);
        // 「いいね」数を取得
        $likeCount = Like::where('spot_id', $id)->count();
        $isFavorited = Auth::check() ? Auth::user()->favorites()->where('spot_id', $id)->exists() : false;
        $isLiked = Auth::check() ? Auth::user()->likes()->where('spot_id', $id)->exists() : false;

        // 詳細ページを表示
        return view('spot.show', compact('spot', 'isFavorited', 'isLiked', 'likeCount'));
    }

    public function addToFavorites($id)
    {
        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // ログインしているユーザーがすでにお気に入り登録しているかチェック
        if (Auth::user()->favoriteSpots()->where('spot_id', $spot->id)->exists()) {
            return redirect()->back()->with('status', 'このスポットはすでにお気に入り登録されています');
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
        $photos = SpotPhoto::where('spot_id', $id)->get();

        // 都道府県を取得
        $prefectures = config('prefectures');

        // 編集ページを表示
        return view('spot.edit', [
            'spot' => $spot,
            'photos' => $photos,
            'prefectures' => $prefectures,
        ]);
    }

    public function update(Request $request, $id)
    {
        // バリデーションルールを取得
        $rules = $this->validationRules();
        $request->validate($rules);

        // IDに該当するスポットを取得
        $spot = Spot::findOrFail($id);

        // フォームリクエストのデータを Spot モデルにセット
        $spot->title = $request->title;
        $spot->prefecture = $request->prefecture;
        $spot->city = $request->city;
        $spot->description = $request->description;
        $spot->date_visited = $request->date_visited;
        $spot->child_age_range = $request->child_age_range;
        $spot->rating = $request->rating;
        $spot->spot_url = $request->spot_url;

        // Spot モデルを保存
        $spot->save();

        // 写真の削除処理
        if ($request->has('delete_photos')) {
            $deletedPhotos = explode(',', $request->input('delete_photos'));
            foreach ($deletedPhotos as $photoId) {
                $photo = SpotPhoto::find($photoId);
                if ($photo) {
                    // ストレージからファイルを削除
                    $photoPath = storage_path('app/public/photos/' . $photo->photo_path);
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }

                    // データベースから写真レコードを削除
                    $photo->delete();
                }
            }
        }

        // 画像がアップロードされている場合は保存処理を行う
        if ($request->hasFile('photos')) {
            $photoCounter = 1;
            foreach ($request->file('photos') as $photo) {
                // ファイル名を生成
                $filename = $spot->id . '_' . $photoCounter . '.' . $photo->getClientOriginalExtension();

                // ファイルを指定のパスに保存する（publicディスクを使用）
                $path = $photo->storeAs('public/photos', $filename);

                // Storage::url を使用して画像のパスを取得
                $photoUrl = Storage::url('photos/' . $filename);

                // SpotPhoto モデルを作成して保存
                $spotPhoto = new SpotPhoto();
                $spotPhoto->spot_id = $spot->id;
                $spotPhoto->photo_path = $photoUrl; // パスを保存
                $spotPhoto->save();

                $photoCounter++; // 画像カウンタをインクリメント
            }
        }

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('spot.show', $spot->id)->with('status', 'スポットが更新されました');
    }


    public function destroy($id)
    {
        // IDに該当するスポットを取得して削除する
        $spot = Spot::findOrFail($id);

        // スポットに関連する写真も削除する
        foreach ($spot->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        // スポットを削除
        $spot->delete();

        // リダイレクトと共に成功メッセージを表示
        return redirect()->route('mypage')->with('status', 'スポットが削除されました');
    }

    public function favorite(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        // お気に入りスポットをクエリビルダーで初期化
        $favoriteSpots = $user->favoriteSpots();

        // 並べ替えの条件
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'newest':
                $favoriteSpots = $favoriteSpots->orderBy('date_visited', 'desc');
                break;
            case 'oldest':
                $favoriteSpots = $favoriteSpots->orderBy('date_visited', 'asc');
                break;
            case 'most_liked':
                $favoriteSpots = $favoriteSpots->withLikeCount()->orderBy('likes_count', 'desc');
                break;
        }

        $favoriteSpots = $favoriteSpots->get(); // クエリを実行して結果を取得

        return view('spot.favorite', compact('favoriteSpots')); // ビューに結果を渡す
    }

    public function shared(Request $request)
    {
        $spots = Spot::query(); // クエリビルダーを初期化

        $query = $request->input('query'); // リクエストからクエリパラメータを取得
        $sort = $request->input('sort', 'newest');

        if ($query) {
            $keywords = explode(' ', $query); // クエリ文字列をスペースで分割
            foreach ($keywords as $keyword) {
                $spots = $spots->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('city', 'like', "%{$keyword}%")
                        ->orWhere('prefecture', 'like', "%{$keyword}%");
                });
            }
        }

        // 並べ替えの条件
        if ($sort === 'newest') {
            $spots = $spots->orderBy('date_visited', 'desc');
        } elseif ($sort === 'oldest') {
            $spots = $spots->orderBy('date_visited', 'asc');
        } elseif ($sort === 'most_liked') {
            $spots = $spots->withCount('likes')->orderBy('likes_count', 'desc');
        }

        $spots = $spots->paginate(10)->withQueryString();

        return view('spot.shared', compact('spots'));
    }
}
