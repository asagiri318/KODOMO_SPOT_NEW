<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'prefecture',
        'city',
        'description',
        'date_visited',
        'child_age_range',
        'rating',
        'photo',
        'spot_url',
    ];

    /**
     * スポットの所有者であるユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
