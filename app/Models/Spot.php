<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'prefecture_id',
        'city',
        'description',
        'date_visited',
        'child_age_range',
        'rating',
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
     * スポットに関連する写真を取得
     */
    public function photos()
    {
        return $this->hasMany(SpotPhoto::class);
    }
}
