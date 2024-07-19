@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="dark:text-white text-3xl font-bold text-center mb-5">プロフィール編集</h1>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="nickname" class="block text-sm font-medium text-gray-700">名前</label>
            <input type="text" name="nickname" id="nickname" value="{{ old('nickname', $user->nickname) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required autofocus>
            @error('nickname')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="profile_photo" class="block text-sm font-medium text-gray-700">プロフィール写真</label>
            <input type="file" name="profile_photo" id="profile_photo" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            @if ($user->photo)
                <div class="mt-2">
                    <img src="{{ Storage::url($user->photo) }}" alt="プロフィール写真" class="w-20 h-20 rounded-full object-cover">
                    <button type="button" class="ml-2 btn-red delete-photo-btn">画像を削除</button>
                </div>
            @endif
            @error('profile_photo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="prefecture" class="block text-sm font-medium text-gray-700">都道府県</label>
            <select name="prefecture" id="prefecture" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                @foreach(config('prefectures') as $index => $prefecture)
                    <option value="{{ $index + 1 }}" {{ old('prefecture', $user->prefecture) == ($index + 1) ? 'selected' : '' }}>{{ $prefecture['name'] }}</option>
                @endforeach
            </select>
            @error('prefecture')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>        

        <div class="mb-4">
            <label for="city" class="block text-sm font-medium text-gray-700">市区町村</label>
            <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            @error('city')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="introduction" class="block text-sm font-medium text-gray-700">自己紹介</label>
            <textarea name="introduction" id="introduction" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ old('introduction', $user->introduction) }}</textarea>
            @error('introduction')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="children-birthdates">
            @if($children->isNotEmpty())
                @foreach($children as $index => $child)
                    <div class="flex items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">お子様の生年月日</label>
                        <input type="date" name="children_birthdates[]" value="{{ old('children_birthdates.' . $index, $child['birthdate']) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        <button type="button" class="ml-2 btn-red" onclick="removeBirthdateField(this)">削除</button>
                    </div>
                @endforeach
            @endif
        </div>

        <button type="button" class="btn-blue mt-2" onclick="addBirthdateField()">お子様の追加</button>

        <div class="mb-4 mt-4 flex justify-between">
            <button type="submit" class="btn-blue">更新する</button>
            <a href="{{ route('mypage') }}" class="btn-blue">キャンセル</a>
        </div>
    </form>

    <form method="POST" action="{{ route('profile.deletePhoto') }}" id="delete-photo-form" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.delete-photo-btn').on('click', function(event) {
        event.preventDefault();
        deleteProfilePhoto();
    });

    window.deleteProfilePhoto = function() {
        $('#delete-photo-form').submit();
    }

    window.addBirthdateField = function() {
        const container = $('#children-birthdates');
        const field = $('<div>').addClass('flex items-center mb-2');
        field.append($('<label>').addClass('block text-sm font-medium text-gray-700').text('お子様の生年月日'));
        field.append($('<input>').addClass('mt-1 block w-full border border-gray-300 rounded-md shadow-sm').attr({ type: 'date', name: 'children_birthdates[]' }));
        field.append($('<button>').addClass('ml-2 btn-red').attr('type', 'button').text('削除').click(function() {
            $(this).parent().remove();
        }));
        container.append(field);
    }

    window.removeBirthdateField = function(button) {
        $(button).parent().remove();
    }
});
</script>
@endsection
