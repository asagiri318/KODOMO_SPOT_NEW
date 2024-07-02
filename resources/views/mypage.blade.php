@extends('layouts.app')

@section('content')
<!-- 登録完了時のアラート -->
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<section class="text-gray-600 body-font min-h-screen">
  <div class="container px-5 py-24 mx-auto flex flex-col">
    <div class="lg:w-4/6 mx-auto">
      <div class="flex flex-col sm:flex-row mt-10">
        <div class="sm:w-1/3 text-center sm:pr-8 sm:py-8">
          <!-- プロフィール写真をクリック可能なリンクに変更 -->
          <a href="{{ route('profile.edit') }}">
            <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-200 text-gray-400 cursor-pointer">
              <img src="{{ $user->profile_photo_url }}" alt="プロフィール写真" class="rounded-full h-20 w-20 object-cover">
            </div>
          </a>
          <div class="flex flex-col items-center text-center justify-center mt-4 sm:mt-0 sm:ml-4">
            <h2 class="font-medium title-font text-gray-900 text-lg">
              {{ $user->nickname }} <!-- ニックネームを表示 -->
            </h2>
            <div class="w-12 h-1 bg-indigo-500 rounded mt-2 mb-4"></div>
          </div>
        </div>
        <div class="sm:w-2/3 sm:pl-8 sm:py-8 sm:border-l border-gray-200 sm:border-t-0 border-t mt-4 pt-4 sm:mt-0 text-center sm:text-left">
          <p class="leading-relaxed text-lg mb-4">
            @if ($user->city)
              住所：{{ $user->prefecture->name }} {{ $user->city }} <!-- 都道府県と市区町村を表示 -->
            @elseif ($user->prefecture_id && $user->prefecture)
              住所：{{ $user->prefecture->name }} <!-- 都道府県のみが選択されている場合は都道府県名のみ表示 -->
            @else
              住所：未設定 <!-- 住所が設定されていない場合の表示 -->
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
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
