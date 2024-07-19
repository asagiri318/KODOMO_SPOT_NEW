@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4">スポットを編集</h1>

        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-500">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('spot.update', $spot->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-700">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $spot->title) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700">説明</label>
                <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded">{{ old('description', $spot->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="prefecture" class="block text-sm font-medium text-gray-700">都道府県</label>
                <select name="prefecture" id="prefecture" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    <option value="">都道府県を選択してください</option>
                    @foreach(config('prefectures') as $prefecture)
                        <option value="{{ $prefecture['name'] }}" {{ old('prefecture', $spot->prefecture) == $prefecture['name'] ? 'selected' : '' }}>{{ $prefecture['name'] }}</option>
                    @endforeach
                </select>
                @error('prefecture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
                 

            <div class="mb-4">
                <label for="city" class="block text-gray-700">市区町村</label>
                <input type="text" name="city" id="city" value="{{ old('city', $spot->city) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="date_visited" class="block text-gray-700">日付</label>
                <input type="date" name="date_visited" id="date_visited" value="{{ old('date_visited', $spot->date_visited) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="child_age_range" class="block text-gray-700">お子様の年齢</label>
                <input type="text" name="child_age_range" id="child_age_range" value="{{ old('child_age_range', $spot->child_age_range) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700">おすすめ度（☆）</label>
                <select name="rating" id="rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    <option value="">選択してください</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('rating', $spot->rating ?? '') == $i ? 'selected' : '' }}>
                            {{ str_repeat('☆', $i) }}
                        </option>
                    @endfor
                </select>
            </div>            

            <div id="photo-fields" class="mb-4">
                <label for="photos" class="block text-sm font-medium text-gray-700 mb-1">写真 (最大3つ)</label>
                @foreach($spot->photos as $photo)
                    <div class="photo-input flex items-center mb-2">
                        <input type="file" name="photos[]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        <button type="button" class="ml-2 btn-red remove-photo-btn">削除</button>
                    </div>
                    <img src="{{ asset($photo->photo_path) }}" class="h-10 w-20 rounded-lg mb-2 mr-2">
                @endforeach
            </div>
            <p id="photo-error" class="text-red-500 text-xs mt-1" style="display: none;">写真は最大3つまで選択できます。</p>
            <button type="button" id="add-photo-btn" class="btn-blue mt-1 mb-4">写真の追加</button>
            
            <div class="mb-4">
                <label for="spot_url" class="block text-gray-700">スポットのURL</label>
                <input type="text" name="spot_url" id="spot_url" value="{{ old('spot_url', $spot->spot_url) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-blue px-4 py-2 rounded">更新</button>
                <a href="{{ route('spot.show', $spot->id) }}" class="btn-gray px-4 py-2 rounded">キャンセル</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let photoFieldsContainer = document.getElementById('photo-fields');
    let addPhotoBtn = document.getElementById('add-photo-btn');
    let maxPhotos = 3;

    addPhotoBtn.addEventListener('click', function() {
        let photoInputs = document.querySelectorAll('#photo-fields .photo-input');
        if (photoInputs.length < maxPhotos) {
            let newPhotoInput = document.createElement('div');
            newPhotoInput.classList.add('photo-input', 'flex', 'items-center', 'mb-2');
            newPhotoInput.innerHTML = `
                <input type="file" name="photos[]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                <button type="button" class="ml-2 btn-red remove-photo-btn">削除</button>
            `;
            photoFieldsContainer.appendChild(newPhotoInput);
            updateRemovePhotoBtns();
        } else {
            document.getElementById('photo-error').style.display = 'block';
        }
    });

    function updateRemovePhotoBtns() {
        let removePhotoBtns = document.querySelectorAll('.remove-photo-btn');
        removePhotoBtns.forEach(function(btn) {
            btn.removeEventListener('click', removePhotoInput);
            btn.addEventListener('click', removePhotoInput);
        });
    }

    function removePhotoInput(event) {
        event.target.parentElement.remove();
        document.getElementById('photo-error').style.display = 'none';
    }

    updateRemovePhotoBtns();
});
</script>
@endsection
