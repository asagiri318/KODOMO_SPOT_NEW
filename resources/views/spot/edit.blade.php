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
                <label for="prefecture_id" class="block text-gray-700">都道府県</label>
                <select name="prefecture_id" id="prefecture_id" class="w-full p-2 border border-gray-300 rounded">
                    @foreach($prefectures as $prefecture)
                        <option value="{{ $prefecture->id }}" {{ $spot->prefecture_id == $prefecture->id ? 'selected' : '' }}>
                            {{ $prefecture->name }}
                        </option>
                    @endforeach
                </select>
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
                <label for="rating" class="block text-gray-700">おすすめ度</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', $spot->rating) }}" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-gray-700">写真</label>
                @if ($spot->photo)
                    <img src="{{ Storage::url($spot->photo) }}" alt="Spot Photo" class="mb-2">
                @endif
                <input type="file" name="photo" id="photo" class="w-full p-2 border border-gray-300 rounded">
            </div>

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
@endsection
