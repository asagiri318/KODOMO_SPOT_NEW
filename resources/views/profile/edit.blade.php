@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold text-center mb-5">プロフィール編集</h1>
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
            <label for="prefecture_id" class="block text-sm font-medium text-gray-700">都道府県</label>
            <select name="prefecture_id" id="prefecture_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                @foreach($prefectures as $prefecture)
                    <option value="{{ $prefecture->id }}" {{ old('prefecture_id', $user->prefecture_id) == $prefecture->id ? 'selected' : '' }}>{{ $prefecture->name }}</option>
                @endforeach
            </select>
            @error('prefecture_id')
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

        <div id="children-birthdates">
            @foreach($children as $index => $child)
                <div class="flex items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">お子様の生年月日</label>
                    <input type="date" name="children_birthdates[]" value="{{ old('children_birthdates.' . $index, optional($child)->birthdate ? $child->birthdate->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                    <button type="button" class="ml-2 btn-red" onclick="removeBirthdateField(this)">削除</button>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn-blue mt-2" onclick="addBirthdateField()">お子様の追加</button>

        <div class="mb-4 flex justify-between">
            <button type="submit" class="btn-blue">更新する</button>
            <a href="{{ route('mypage') }}" class="btn-blue">キャンセル</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // 初期値をセットする
    var initialPrefectureId = $('#prefecture_id').val();
    if (initialPrefectureId) {
        $('#prefecture_id').trigger('change');
    }

    // Ajaxリクエストを送信する
    $('#prefecture_id').on('change', function() {
        var prefecture_id = $(this).val();
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('ajax.cities') }}",
            data: { prefecture_id: prefecture_id },
            dataType: "json",
            success: function(response) {
                var $citySelect = $('#city_id');
                $citySelect.empty();
                $.each(response.data, function(index, city) {
                    $citySelect.append($('<option></option>').attr('value', city.id).text(city.name));
                });
            },
            error: function() {
                console.log('市区町村の取得に失敗しました。');
            }
        });
    });

    // 子供の生年月日フィールドの追加・削除
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
