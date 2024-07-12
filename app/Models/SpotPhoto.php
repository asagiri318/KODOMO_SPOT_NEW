<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SpotPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['spot_id', 'photo_path'];

    /**
     * スポット写真のフルパスを取得
     */
    public function getPhotoUrlAttribute()
    {
        return Storage::url($this->photo_path);
    }

    /**
     * この写真が属するスポットを取得
     */
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
