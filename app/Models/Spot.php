<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'prefecture',
        'city',
        'description',
        'date_visited',
        'child_age_range',
        'rating',
        'photo',
        'spot_url',
    ];

    /**
     * スポットの所有者であるユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * スポットが属する都道府県を取得
     */
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    /**
     * このスポットをお気に入り登録しているユーザーを取得
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorite_spots', 'spot_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * バリデーションルールを定義
     */
    public static function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'prefecture_id' => 'required|exists:prefectures,id',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'date_visited' => 'required|date',
            'child_age_range' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048', // 画像の最大サイズは2048KB (2MB)
            'spot_url' => 'nullable|url|max:255',
        ];
    }
}
