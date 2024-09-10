<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nickname',
        'email',
        'password',
        'photo', // プロフィール写真のカラム名を修正
        'prefecture', // 都道府県ID
        'city', // 市区町村
        'introduction',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // FIXME: prefecture テーブルがないため不要なメソッド
    // 都道府県とのリレーションシップ
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    // ユーザーに birthdate カラムがないため不要です。
    // 年齢を計算するメソッド
    public function getAge()
    {
        if ($this->birthdate) {
            return Carbon::parse($this->birthdate)->age;
        }
        return null;
    }

    // 子供の生年月日情報とのリレーションシップ
    public function children()
    {
        return $this->hasMany(UserChild::class);
    }

    // ユーザーが登録したスポットとのリレーションシップ
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    // お気に入りしたスポットとのリレーションシップ
    public function favoriteSpots()
    {
        return $this->belongsToMany(Spot::class, 'user_favorite_spots', 'user_id', 'spot_id')->withTimestamps();
    }

    // FIXME: 一つ上のメソッドとほぼ同じ？どこでも使われていないので削除でよろしいです。
    public function favorites()
    {
        return $this->belongsToMany(Spot::class, 'user_favorite_spots', 'user_id', 'spot_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Spot::class, 'likes', 'user_id', 'spot_id');
    }
}
