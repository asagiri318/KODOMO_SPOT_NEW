@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-4">みんなの共有スポット</h1>
        
        @if($spots->isEmpty())
            <p>まだスポットが登録されていません。</p>
        @else
            <ul class="space-y-4">
                @foreach($spots as $spot)
                    <li class="mb-4 flex flex-col sm:flex-row items-center p-4 border rounded-md hover:bg-gray-100 transition cursor-pointer" onclick="location.href='{{ route('spot.show', $spot->id) }}'">
                        <div class="flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 overflow-hidden mb-4 sm:mb-0">
                            @if ($spot->photos->isNotEmpty())
                                <img src="{{ asset($spot->photos->first()->photo_path) }}" alt="Spot Image" class="w-full h-auto object-cover rounded-md max-h-64">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">No image</div>
                            @endif
                        </div>
                        <div class="ml-0 sm:ml-4 flex-1">
                            <h2 class="font-bold text-lg">{{ $spot->title }}</h2>
                            <p class="text-sm mt-2">{{ Str::limit($spot->description, 100) }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                @if ($spot->prefecture)
                                    {{ $spot->prefecture->name }} {{ $spot->city }}
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
@endsection
