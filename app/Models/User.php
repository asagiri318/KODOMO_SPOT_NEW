<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 保存リストとのリレーションシップを追加
    public function savedSpots()
    {
        return $this->hasMany(SavedSpot::class);
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
}
