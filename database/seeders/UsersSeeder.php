<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // 既存のユーザー情報を取得するクエリ
        $users = User::all();

        // 取得したユーザー情報をシーダーとしてデータベースに挿入
        foreach ($users as $user) {
            // 重複するメールアドレスが存在しないか確認してから挿入する
            if (!User::where('email', $user->email)->exists()) {
                \DB::table('users')->insert([
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                    'password' => bcrypt($user->password), // パスワードをハッシュ化して保存
                    'photo' => $user->photo,
                    'prefecture_id' => $user->prefecture_id,
                    'city' => $user->city,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
