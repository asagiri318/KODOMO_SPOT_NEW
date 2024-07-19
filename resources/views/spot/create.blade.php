@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="dark:text-white text-3xl font-bold text-center mb-5">新規スポット登録</h1>
    <form method="POST" action="{{ route('spot.store') }}" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">タイトル</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required placeholder="場所の名前">
        </div>

        <div class="mb-4">
            <label for="prefecture" class="block text-sm font-medium text-gray-700">都道府県</label>
            <select name="prefecture" id="prefecture" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                <option value="">都道府県を選択してください</option>
                @foreach($prefectures as $prefecture)
                <option value="{{ $prefecture['name'] }}">{{ $prefecture['name'] }}</option>
                @endforeach
            </select>                 
        </div>

        <div class="mb-4">
            <label for="city" class="block text-sm font-medium text-gray-700">市区町村</label>
            <input type="text" name="city" id="city" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">内容</label>
            <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required placeholder="こんな所・こんな事した・おすすめポイントなど"></textarea>
        </div>

        <div class="mb-4">
            <label for="date_visited" class="block text-sm font-medium text-gray-700">日付</label>
            <input type="date" name="date_visited" id="date_visited" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="child_age_range" class="block text-sm font-medium text-gray-700">お子様の年齢（訪れた日）</label>
            <input type="text" name="child_age_range" id="child_age_range" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required placeholder="5歳・3歳">
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
            <label for="photos" class="block text-sm font-medium text-gray-700">写真 (最大3つ)</label>
            <div class="photo-input flex items-center mb-2">
                <input type="file" name="photos[]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                <button type="button" class="ml-2 btn-red remove-photo-btn">削除</button>
            </div>
        </div>

        <p id="photo-error" class="text-red-500 text-xs mt-1" style="display: none;">写真は最大3つまで選択できます。</p>
        <button type="button" id="add-photo-btn" class="btn-blue mt-1 mb-4">写真の追加</button>
        
        <div class="mb-4">
            <label for="spot_url" class="block text-sm font-medium text-gray-700">スポットのURL</label>
            <input type="url" name="spot_url" id="spot_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="text-center">
            <button type="submit" class="btn-blue">登録</button>
        </div>
    </form>
</div>

@if(session('status'))
<script>
    window.onload = function() {
        alert('{{ session('status') }}');
    }
</script>
@endif

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
