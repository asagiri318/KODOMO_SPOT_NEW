@extends('layouts.app')

@section('content')
@if(session('status'))
    <div class="mt-2 dark:text-white alert alert-success">
        {{ session('status') }}
    </div>
@endif

<section class="text-gray-600 body-font min-h-screen">
<div class="relative">
    <img src="{{ asset('images/profile-banner.jpg') }}" alt="プロフィールバナー" class="w-full object-cover mb-2">
    <div class="absolute top-0 left-0 right-0 p-4 text-center text-white">
        <div class="p-6 rounded-lg">
            <h1 class="text-[6vw] font-bold font-kalnia">今日はどこに行きましたか？</h1>
        </div>
    </div>
</div>

<div class="container px-4 mt-4 mx-auto">
    <div class="bg-white p-4 rounded-lg shadow-md mb-4 flex flex-col items-center">
        <div class="flex justify-center items-center">
            <div class="w-1/3 text-center pr-4 py-2">
                <div class="w-20 h-20 rounded-full inline-flex bg-gray-200 text-gray-400 cursor-pointer" onclick="openModal()">
                    @if ($user->photo)
                      <img src="{{ asset('storage/' . $user->photo) }}" alt="プロフィール写真" class="rounded-full h-full w-full object-cover">
                    @else
                      <span class="text-3xl">+</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-center text-center justify-center ml-4">
                <a href="{{ route('profile.edit') }}" class="relative">
                    <h2 class="font-medium title-font text-blue-600 text-2xl truncate hover:text-indigo-200 w-40 sm:w-60">
                        {{ $user->nickname }} <!-- ニックネームを表示 -->
                    </h2>
                </a>
                <p class="leading-relaxed text-base">
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
            </div> 
        </div>
        <div class="sm:w-full border-gray-200 border-t pt-4 sm:mt-0 text-center">               
            <p class="leading-relaxed text-lg mb-2">
                @if ($children->isNotEmpty())
                    お子様の年齢：
                    @foreach ($children as $child)
                        {{ \Carbon\Carbon::parse($child->birthdate)->age }} 歳{{ !$loop->last ? ' & ' : '' }}
                    @endforeach
                @else
                    お子様の情報が登録されていません。
                @endif
            </p>
            <div class="mb-2">
                <label for="introduction" class="font-medium border-gray-400 text-gray-700">【自己紹介】</label>
                <p class="mt-1 p-1">{!! nl2br(e($user->introduction ?? '未設定')) !!}</p>
            </div>
        </div>
    </div>
</div>

<!-- スポット一覧の表示 -->
<div class="relative">
        <h1 class="text-center text-[6vw] font-black text-black">My登録スポット一覧</h1>
    </div>

<div class="container px-4 mt-3 mx-auto">
    <div class="flex justify-between mb-2 flex-nowrap">
        <form method="GET" action="{{ route('mypage') }}" class="flex items-center space-x-2" onsubmit="saveScrollPosition()">
            <input type="text" name="query" placeholder="キーワードを入力" value="{{ request('query') }}" class="rounded px-1 py-2">
            <button type="submit" class="bg-blue-500 text-white px-2 py-2 rounded whitespace-nowrap">検索</button>
        </form>
    
        <div class="ml-2 flex-shrink-0">
            <select name="sort" id="sort" class="border rounded py-2 px-2 text-xs text-left pr-8">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>新しい順</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>古い順</option>
                <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>人気順</option>
            </select>
        </div>
    </div>

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
                                訪れた日: {{ \Carbon\Carbon::parse($spot->date_visited)->format('Y年m月d日') }}
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
  <div class="bg-white rounded-lg p-6">
    <button onclick="closeModal()" class="absolute top-4 right-4 text-xl">✖️</button>
    <img id="modalImage" src="{{ asset('storage/' . $user->photo) }}" alt="プロフィール写真" class="rounded-lg cursor-pointer" onclick="closeModal()">
  </div>
</div>

<script>
    // スクロール位置を保存する関数
    function saveScrollPosition() {
        localStorage.setItem('scrollPosition', window.scrollY);
    }

    // スクロール位置を復元する関数
    function restoreScrollPosition() {
        const scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition));
            localStorage.removeItem('scrollPosition');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        restoreScrollPosition(); // ページ読み込み時にスクロール位置を復元

        document.getElementById('sort').addEventListener('change', function() {
            saveScrollPosition(); // ソート変更時にスクロール位置を保存
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
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('modal').classList.remove('flex');
    }
</script>

@endsection
