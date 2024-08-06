<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addToFavorites($id)
    {
        $user = Auth::user();

        // すでにお気に入りに追加されているか確認
        if ($user->favoriteSpots()->where('spot_id', $id)->exists()) {
            return redirect()->back()->with('info', 'このスポットはすでにお気に入りに追加されています。');
        }

        $user->favoriteSpots()->attach($id);

        return redirect()->back()->with('success', 'スポットをお気に入りに追加しました。');
    }

    public function removeFromFavorites($id)
    {
        $user = Auth::user();
        $user->favoriteSpots()->detach($id);

        return redirect()->back()->with('success', 'スポットをお気に入りから削除しました。');
    }

    public function index()
    {
        $user = Auth::user();
        $favoriteSpots = $user->favoriteSpots;

        return view('spot.favorite', compact('favoriteSpots'));
    }
}
