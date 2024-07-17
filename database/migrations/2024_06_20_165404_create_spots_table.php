<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotsTable extends Migration
{
    public function up()
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('prefecture');
            $table->string('city');
            $table->text('description')->nullable();
            $table->date('date_visited')->nullable();
            $table->string('child_age_range')->nullable();
            $table->integer('rating')->nullable();
            $table->string('spot_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('spots', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['prefecture']);
        });

        Schema::dropIfExists('spots');
    }
}
