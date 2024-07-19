<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Spot;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($spotId)
    {
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)->where('spot_id', $spotId)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            Like::create([
                'user_id' => $user->id,
                'spot_id' => $spotId,
            ]);
            return response()->json(['liked' => true]);
        }
    }

    public function likeCount($spotId)
    {
        $count = Like::where('spot_id', $spotId)->count();
        return response()->json(['count' => $count]);
    }
}
