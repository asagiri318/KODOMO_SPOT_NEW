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
        'profile_photo_url',
        'prefecture_id',  // 都道府県ID
        'city',           // 市区町村
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 都道府県とのリレーションシップ
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    // 年齢を計算するメソッド
    public function getAge()
    {
        if ($this->birthdate) {
            return Carbon::parse($this->birthdate)->age;
        }
        return null;
    }

    // ProfileController で使用する save メソッドをオーバーライド
    public function save(array $options = [])
    {
        // ここに保存処理の実装を追加する
        parent::save($options);
    }

    // ProfileController で使用する delete メソッドをオーバーライド
    public function delete(array $options = [])
    {
        // ここに削除処理の実装を追加する
        parent::delete($options);
    }

    // 子供の生年月日情報とのリレーションシップ
    public function childrenAges()
    {
        return $this->hasMany(UserChild::class);
    }
}
