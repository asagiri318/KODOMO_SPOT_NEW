@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">成功!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <h1 class="text-3xl font-bold mb-4">{{ $spot->title }}</h1>
        <p class="mb-4">{{ $spot->description }}</p>
        <p class="mb-2">場所:
            @if ($spot->prefecture)
                {{ $spot->prefecture->name }}
            @endif
            {{ $spot->city }}</p>
        <p class="mb-2">日付: {{ $spot->date_visited }}</p>
        <p class="mb-2">お子様の年齢: {{ $spot->child_age_range }}</p>
        <p class="mb-4">おすすめ度: {{ $spot->rating }} ☆</p>
        @if($spot->photo)
            <img src="{{ Storage::url($spot->photo) }}" alt="{{ $spot->title }}" class="mb-4">
        @endif
        @if($spot->spot_url)
            <p>スポットのURL: <a href="{{ $spot->spot_url }}" target="_blank" class="text-blue-500">{{ $spot->spot_url }}</a></p>
        @endif

        <div class="mt-4 flex justify-between">
            <!-- お気に入り登録ボタンまたは登録済みメッセージ -->
            @if($isFavorited)
                <span class="text-green-500 px-4 py-2 rounded border border-green-500">お気に入り登録済みです</span>
            @else
                <form action="{{ route('spot.addToFavorites', $spot->id) }}" method="POST" class="inline-block mr-2">
                    @csrf
                    <button type="submit" class="btn-blue px-4 py-2 rounded">お気に入り登録</button>
                </form>
            @endif
            
            <!-- 編集ボタン -->
            <a href="{{ route('spot.edit', $spot->id) }}" class="btn-green px-4 py-2 rounded mr-2">編集</a>
            
            <!-- 削除ボタン -->
            <form action="{{ route('spot.destroy', $spot->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-red px-4 py-2 rounded">削除</button>
            </form>
        </div>
    </div>
</div>
@endsection
