<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChildrenTable extends Migration
{
    public function up()
    {
        Schema::create('user_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('birthdate')->nullable(); // birthdate フィールドを追加
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_children');
    }
}
