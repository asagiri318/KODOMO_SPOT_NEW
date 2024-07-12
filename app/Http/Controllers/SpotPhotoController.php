<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpotPhoto;
use Illuminate\Support\Facades\Storage;

class SpotPhotoController extends Controller
{
    /**
     * 写真をアップロードする
     */
    public function store(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'photos.*' => 'required|image|max:2048', // 画像の最大サイズは2048KB (2MB)
        ]);

        foreach ($request->file('photos') as $photo) {
            $filename = hash('sha256', $photo->getClientOriginalName() . time()) . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/photos', $filename);
            $photoPath = str_replace('public/', '', $path);

            SpotPhoto::create([
                'spot_id' => $request->input('spot_id'),
                'photo_path' => $photoPath,
            ]);
        }

        return redirect()->back()->with('success', '写真がアップロードされました');
    }

    /**
     * 写真を更新する
     */
    public function update(Request $request, SpotPhoto $photo)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // 画像の最大サイズは2048KB (2MB)
        ]);

        // 既存の写真を削除
        Storage::disk('public')->delete($photo->photo_path);

        // 新しい写真を保存
        $file = $request->file('photo');
        $filename = hash('sha256', $file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/photos', $filename);
        $photoPath = str_replace('public/', '', $path);

        $photo->update(['photo_path' => $photoPath]);

        return redirect()->back()->with('success', '写真が更新されました');
    }

    /**
     * 写真を削除する
     */
    public function destroy(SpotPhoto $photo)
    {
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return redirect()->back()->with('success', '写真が削除されました');
    }
}
