@extends('layouts.app')

@section('content')
<!-- 登録完了時のアラート -->
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto flex flex-col">
    <div class="lg:w-4/6 mx-auto">
      <div class="flex flex-col sm:flex-row mt-10">
        <div class="sm:w-1/3 text-center sm:pr-8 sm:py-8">
          <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-200 text-gray-400">
            <img src="{{ $user->profile_photo_url }}" alt="プロフィール写真" class="rounded-full h-20 w-20 object-cover">
          </div>
          <div class="flex flex-col items-center text-center justify-center mt-4 sm:mt-0 sm:ml-4">
            <h2 class="font-medium title-font text-gray-900 text-lg">
              {{ $user->name }}
            </h2>
            <p class="text-gray-600">
              {{ $user->prefecture }}, {{ $user->city }}
            </p>
            <div class="w-12 h-1 bg-indigo-500 rounded mt-2 mb-4"></div>
            <p class="text-base">
              ここにユーザーの自己紹介文が入ります。詳細な説明や自己紹介などを追加できます。
            </p>
          </div>
        </div>
        <div class="sm:w-2/3 sm:pl-8 sm:py-8 sm:border-l border-gray-200 sm:border-t-0 border-t mt-4 pt-4 sm:mt-0 text-center sm:text-left">
          <p class="leading-relaxed text-lg mb-4">
            ここにユーザーの詳細情報が表示されます。例えば、ユーザーが保存したスポットの一覧やその他の情報を表示することができます。
          </p>
          <a class="text-indigo-500 inline-flex items-center" href="#">
            もっと詳しく知る
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
              <path d="M5 12h14M12 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

