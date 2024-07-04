@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4">お気に入り一覧</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">成功!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">情報!</strong>
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif

        @if($favoriteSpots->isEmpty())
            <p>お気に入り登録したスポットはありません。</p>
        @else
            <ul>
                @foreach($favoriteSpots as $spot)
                    <li class="mb-4">
                        <div class="flex justify-between items-center">
                            <span>
                                ・{{ $spot->title }}
                                <span class="text-sm text-gray-500">
                                    @if ($spot->prefecture)
                                        {{ $spot->prefecture->name }}
                                    @endif
                                    {{ $spot->city }}
                                </span>
                                <span class="text-sm text-yellow-500">
                                    @for ($i = 0; $i < $spot->rating; $i++)
                                        ☆
                                    @endfor
                                </span>
                            </span>
                            <div class="flex">
                                <a href="{{ route('spot.show', $spot->id) }}" class="btn-blue">詳細を表示</a>
                                <form action="{{ route('favorites.remove', $spot->id) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    <button type="submit" class="btn-red">お気に入りから削除</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
