<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AjaxController extends Controller
{
    /**
     * 都道府県に応じた市区町村の取得
     */
    public function getCities(Request $request)
    {
        $prefecture_id = $request->prefecture_id;
        $response = Http::get("https://www.land.mlit.go.jp/webland/api/CitySearch?area={$prefecture_id}");
        return $response->json();
    }
}
