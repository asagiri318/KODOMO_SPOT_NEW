<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function removeFromFavorites(Spot $spot)
    {
        $user = Auth::user();
        $user->favoriteSpots()->detach($spot->id);

        return redirect()->back()->with('success', 'スポットをお気に入りから削除しました。');
    }

    public function index()
    {
        $user = Auth::user();
        $favoriteSpots = $user->favoriteSpots;

        return view('spot.favorite', compact('favoriteSpots'));
    }
}
