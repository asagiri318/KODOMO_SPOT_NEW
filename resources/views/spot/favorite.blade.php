@extends('layouts.app')

@section('content')

<style>
    .bg-spotfv {
        background-image: url('{{ asset('images/profile-fvbg.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat; /* 画像の繰り返しを防ぐ */
        background-attachment: fixed; /* スクロール時に背景画像が固定される */
        margin: 0; /* 上下の余白をリセット */
    }
    </style>

<div class="bg-spotfv pb-4">
    <div class="container mx-auto px-10 pb-90">
        <h1 class="text-4xl font-bold mb-3 pt-3 text-center">お気に入り一覧</h1>
    <div class="flex justify-between mb-2">
        <select name="sort" id="sort" class="border rounded py-2 px-4 text-xs text-left pr-8">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>新しい順</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>古い順</option>
            <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>人気順</option>
        </select>
    </div> 

    <div class="max-w-4xl mx-auto bg-white p-2 rounded-lg shadow-md">
        @if($favoriteSpots->isEmpty())
            <p>まだスポットが登録されていません。</p>
        @else
            <ul class="space-y-4">
                @foreach($favoriteSpots as $spot)
                <li class="mb-4 p-2 border rounded-md hover:bg-gray-100 transition cursor-pointer" onclick="location.href='{{ route('spot.show', $spot->id) }}'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-20 h-20 sm:w-32 sm:h-32 overflow-hidden">
                            @if ($spot->photos->isNotEmpty())
                                <img src="{{ asset($spot->photos->first()->photo_path) }}" alt="Spot Image" class="rounded-md h-full w-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">No image</div>
                            @endif
                        </div>
                        <div class="ml-4 sm:ml-8 flex-1">
                            <h2 class="text-lg">{{ Str::limit($spot->title, 15)}}</h2>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sort').addEventListener('change', function() {
            let selectedOption = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', selectedOption);
            window.location.href = url.toString();
        });
    });
</script>

@endsection
