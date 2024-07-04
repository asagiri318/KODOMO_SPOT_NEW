<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefecturesTable extends Migration
{
    public function up()
    {
        Schema::create('prefectures', function (Blueprint $table) {
            $table->id(); // 自動増分のIDを追加
            $table->string('name')->unique(); // 都道府県名を保存するカラム
            $table->timestamps(); // 作成日時と更新日時を追加
        });
    }

    public function down()
    {
        Schema::dropIfExists('prefectures');
    }
}
