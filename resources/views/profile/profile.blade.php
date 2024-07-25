@extends('layouts.app')

@section('content')
<section class="text-gray-600 body-font min-h-screen">

    <h2 class="text-center text-2xl font-bold mt-4">
      {{ $user->nickname }}
    </h2>

  <div class="container px-5 py-4 mx-auto">
    <div class="bg-white rounded-lg shadow-md">
      <div class="flex flex-col sm:flex-row items-center sm:items-start">
        <div class="w-1/3 text-center py-2">
          <div class="w-20 h-20 rounded-full inline-flex bg-gray-200 text-gray-400 cursor-pointer" onclick="openModal()" data-original-url="{{ url()->current() }}">
            @if ($user->photo)
              <img src="{{ asset('storage/' . $user->photo) }}" alt="プロフィール写真" class="rounded-full h-full w-full object-cover">
            @else
              <span class="text-3xl">+</span>
            @endif
          </div>
        </div>
        <div class="sm:w-2/3 sm:pl-8 sm:py-8 sm:border-l border-gray-200 sm:border-t-0 border-t pt-4 sm:mt-0 text-center sm:text-left">
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
    <div class="mt-4">
      <h2 class="text-center text-2xl font-bold mb-3">
        {{ $user->nickname }}さんの登録スポット</h2>
      <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">      
        @if($spots->isEmpty())
            <p>登録されたスポットはありません。</p>
        @else
            <ul class="space-y-4">
                @foreach($spots as $spot)
                <li class="p-2 border hover:bg-gray-100 transition cursor-pointer" onclick="location.href='{{ route('spot.show', $spot->id) }}'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-20 h-20 sm:w-32 sm:h-32 overflow-hidden mr-4">
                            @if ($spot->photos->isNotEmpty())
                                <img src="{{ asset($spot->photos->first()->photo_path) }}" alt="Spot Image" class="rounded-md h-full w-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 rounded-md">No image</div>
                            @endif
                        </div>
                        <div class="ml-1 sm:ml-8 flex-1">
                            <h2 class="font-bold">
                                {{ Str::limit($spot->title, 23)}}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                @if ($spot->prefecture)
                                    {{ $spot->prefecture }} {{ $spot->city }}
                                @else
                                    {{ $spot->city }}
                                @endif
                            </p>
                             <p class="text-sm text-gray-500 mt-1">
                              登録日: {{ \Carbon\Carbon::parse($spot->date_visited)->format('Y年m月d日') }}
                          </p>
                      </div>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
</div>
</section>

<!-- モーダルの実装 -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white rounded-lg p-6 relative">
    <button onclick="closeModal()" class="text-right mb-4">✖️</button>
    <img id="modalImage" src="{{ asset('storage/' . $user->photo) }}" alt="プロフィール写真" class="rounded-lg cursor-pointer" onclick="closeModal()">
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

document.addEventListener('DOMContentLoaded', function() {
  var nicknameElement = document.querySelector('.nickname');
  var nicknameWidth = nicknameElement.offsetWidth;

  var underlineElement = nicknameElement.querySelector('.underline');
  underlineElement.style.width = nicknameWidth + 'px';
});

function openModal() {
  document.getElementById('modal').classList.remove('hidden');
  document.getElementById('modal').classList.add('flex');
  // `data-original-url` から元のページの URL を取得して保存
  window.originalUrl = event.currentTarget.getAttribute('data-original-url');
}

function closeModal() {
  document.getElementById('modal').classList.add('hidden');
  document.getElementById('modal').classList.remove('flex');
  // 保存した元のページの URL にリダイレクト
  if (window.originalUrl) {
    window.location.href = window.originalUrl;
  } else {
    window.location.href = '{{ route("mypage") }}'; // デフォルトでマイページに戻る
  }
}

</script>
@endsection
