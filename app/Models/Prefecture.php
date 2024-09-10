<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    // FIXME: prefecture テーブルがないためモデルファイルは不要です。

    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
