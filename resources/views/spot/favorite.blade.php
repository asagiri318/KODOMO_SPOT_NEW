@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4">お気に入り一覧</h1>
        
        @if($favoriteSpots->isEmpty())
            <p>まだスポットが登録されていません。</p>
        @else
            <ul class="space-y-4">
                @foreach($favoriteSpots as $spot)
                <li class="mb-4 p-4 border rounded-md hover:bg-gray-100 transition cursor-pointer" onclick="location.href='{{ route('spot.show', $spot->id) }}'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-20 h-20 sm:w-32 sm:h-32 overflow-hidden mr-4">
                            @if ($spot->photos->isNotEmpty())
                                <img src="{{ asset($spot->photos->first()->photo_path) }}" alt="Spot Image" class="rounded-md h-full w-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">No image</div>
                            @endif
                        </div>
                        <div class="ml-4 sm:ml-8 flex-1">
                            <h2 class="font-bold text-lg">{{ $spot->title }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                @if ($spot->prefecture)
                                    {{ $spot->prefecture }} {{ $spot->city }}
                                @else
                                    {{ $spot->city }}
                                @endif
                            </p>
                            <p class="text-sm text-yellow-500 mt-1">
                                @for ($i = 0; $i < $spot->rating; $i++)
                                    ☆
                                @endfor
                            </p>
                        </div>
                        <div class="ml-auto">
                            <form action="{{ route('favorites.remove', $spot->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-red text-xs">外す</button>
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
