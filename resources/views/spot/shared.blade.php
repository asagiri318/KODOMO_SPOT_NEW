@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <h1 class="text-3xl font-bold mb-4 dark:text-white">みんなの共有スポット</h1>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        @if($spots->isEmpty())
            <p>まだスポットが登録されていません。</p>
        @else
            <ul class="space-y-4">
                @foreach($spots as $spot)
                    <li class="mb-4 flex flex-col p-4 border rounded-md hover:bg-gray-100 transition cursor-pointer" onclick="location.href='{{ route('spot.show', $spot->id) }}'">
                        <h2 class="mb-2 font-bold text-lg">{{ $spot->title }}</h2>
                        <div class="relative flex-shrink-0 w-full overflow-hidden mb-4">
                            @if ($spot->photos->isNotEmpty())
                                <img src="{{ asset($spot->photos->first()->photo_path) }}" alt="Spot Image" class="w-full h-auto object-cover rounded-md max-h-64">
                            @else
                                <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">No image</div>
                            @endif
                            <div class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-lg z-10">
                                <span class="like-button text-red-500 text-3xl cursor-pointer" data-id="{{ $spot->id }}">♡</span>
                                <span class="like-count text-gray-800 text-sm ml-2" data-id="{{ $spot->id }}">0</span>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-sm mt-2">{{ Str::limit($spot->description, 50) }}</p>
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
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const likeButtons = document.querySelectorAll('.like-button');

        likeButtons.forEach(button => {
            const spotId = button.getAttribute('data-id');
            const likeCountElement = document.querySelector(`.like-count[data-id="${spotId}"]`);

            // 初期いいね数の取得
            fetch(`/spots/${spotId}/like-count`)
                .then(response => response.json())
                .then(data => {
                    likeCountElement.textContent = data.count;
                });

            // いいねボタンのクリックイベント
            button.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent triggering the parent click event

                fetch(`/spots/${spotId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.liked) {
                        button.textContent = '❤️';
                    } else {
                        button.textContent = '♡';
                    }

                    // いいね数の更新
                    fetch(`/spots/${spotId}/like-count`)
                        .then(response => response.json())
                        .then(data => {
                            likeCountElement.textContent = data.count;
                        });
                });
            });
        });
    });
</script>
@endsection
