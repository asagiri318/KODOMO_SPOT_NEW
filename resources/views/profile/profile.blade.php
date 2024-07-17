@extends('layouts.app')

@section('content')
<section class="text-gray-600 body-font min-h-screen">
  <div class="container px-5 py-24 mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
      <div class="flex flex-col sm:flex-row items-center sm:items-start">
        <div class="sm:w-1/3 text-center sm:pr-8 sm:py-8">
          <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-200 text-gray-400">
            @if ($user->photo)
              <img src="{{ asset('storage/' . $user->photo) }}" alt="プロフィール写真" class="rounded-full h-20 w-20 object-cover">
            @else
              <span class="text-3xl">+</span>
            @endif
          </div>
          <div class="flex flex-col items-center text-center justify-center mt-4 sm:mt-0 sm:ml-4">
            <h2 class="font-medium title-font text-gray-900 text-lg">
              {{ $user->nickname }}
            </h2>
            <div class="w-12 h-1 bg-indigo-500 rounded mt-2 mb-4"></div>
          </div>
        </div>
        <div class="sm:w-2/3 sm:pl-8 sm:py-8 sm:border-l border-gray-200 sm:border-t-0 border-t mt-4 pt-4 sm:mt-0 text-center sm:text-left">
          <p class="leading-relaxed text-lg mb-4">
            @if ($user->prefecture)
                @php
                    $prefectureId = $user->prefecture;
                    $prefectureName = config('prefectures')[$prefectureId - 1]['name'] ?? null;
                @endphp
                @if ($prefectureName)
                    住所：{{ $prefectureName }} <!-- 都道府県の名前 -->
                @else
                    住所：未設定 <!-- 対応する都道府県が見つからない場合の表示 -->
                @endif
                @if ($user->city)
                    &nbsp;{{ $user->city }} <!-- 市区町村 -->
                @endif
            @else
                住所：未設定 <!-- 都道府県が設定されていない場合の表示 -->
            @endif
        </p>        
          <p class="leading-relaxed text-lg mb-4">
            @if ($children->isNotEmpty())
                お子様の年齢：
                @foreach ($children as $child)
                    {{ \Carbon\Carbon::parse($child->birthdate)->age }} 歳{{ !$loop->last ? ' & ' : '' }}
                @endforeach
            @else
                お子様の情報が登録されていません。
            @endif
          </p>
          <div class="mb-4">
            <label for="introduction" class="text-sm font-medium text-gray-700">自己紹介</label>
            <p class="mt-1 p-2">{{ $user->introduction ?? '未設定' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- スポット一覧の表示 -->
    <div class="mt-10">
      <h2 class="text-2xl font-bold mb-4">登録したスポット</h2>
      @if($spots->isEmpty())
          <p>登録されたスポットはありません。</p>
      @else
          <ul>
              @foreach($spots as $spot)
                  <li class="mb-4">
                      <div class="flex justify-between items-center">
                          <span>
                              ・{{ $spot->title }}
                              <span class="text-sm text-gray-500">&nbsp;{{ $spot->prefecture }}&nbsp;{{ $spot->city }}</span>
                              <span class="text-sm text-yellow-500">
                                  @for ($i = 0; $i < $spot->rating; $i++)
                                      ☆
                                  @endfor
                              </span>
                          </span>
                          <a href="{{ route('spot.show', $spot->id) }}" class="btn-blue">詳細を表示</a>
                      </div>
                  </li>
              @endforeach
          </ul>
      @endif
    </div>

  </div>
</section>
@endsection
