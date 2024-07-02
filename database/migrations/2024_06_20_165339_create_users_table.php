<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->default('Guest'); // ユーザーの名前、デフォルト値を設定
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_photo_url')->nullable(); // プロフィール写真のURL
            $table->foreignId('prefecture_id')->nullable()->constrained('prefectures'); // 都道府県ID
            $table->string('city')->nullable(); // 市区町村の文字列
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
