@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5 px-4">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">成功!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <h1 class="text-3xl font-bold mb-4">{{ $spot->title }}</h1>
        <p class="mb-2">場所:
            @if ($spot->prefecture)
                {{ $spot->prefecture }}
            @endif
            {{ $spot->city }}</p>
        <p class="mb-2">日付: {{ $spot->date_visited }}</p>
        <!-- 投稿者のプロフィール写真とニックネーム -->
        <div class="flex items-center mb-4">
            <div class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-200 text-gray-400">
                @if ($spot->user->photo)
                    <img src="{{ asset('storage/' . $spot->user->photo) }}" alt="プロフィール写真" class="rounded-full h-20 w-20 object-cover">
                @else
                    <span class="text-3xl">+</span>
                @endif
            </div>
            <h2 class="font-medium title-font text-gray-900 text-lg ml-4">
                <a href="{{ route('user.profile', $spot->user->id) }}" class="text-gray-900 hover:text-indigo-500">
                    {{ $spot->user->nickname }} さん
                </a>
            </h2>
        </div>
        <p class="mb-2">お子様の年齢（訪れた日）: {{ $spot->child_age_range }}</p>

        <p class="mb-4">{!! nl2br(e($spot->description)) !!}</p>

        <!-- 画像の表示 -->
        <div class="mb-4 flex flex-wrap">
            @foreach($spot->photos as $index => $spotPhoto)
                <div class="relative group w-1/3 p-2"> <!-- 横並びにするために幅を1/3に設定 -->
                    <img src="{{ asset($spotPhoto->photo_path) }}" alt="{{ $spot->title }}" class="w-full h-full transition duration-300 transform group-hover:scale-110 cursor-pointer object-cover rounded-md"
                         onclick="toggleModal('{{ asset($spotPhoto->photo_path) }}', '{{ $spot->title }}')">
                </div>
            @endforeach
        </div>

        <p class="mb-4">おすすめ度: {{ $spot->rating }} ☆</p>

        @if($spot->spot_url)
            <p>スポットのURL: <a href="{{ $spot->spot_url }}" target="_blank" class="text-blue-500">{{ $spot->spot_url }}</a></p>
        @endif

        <div class="mt-4 flex justify-between">
            <!-- お気に入り登録ボタンまたは登録済みメッセージ -->
            @if($isFavorited)
                <span class="text-green-500 px-4 py-2 rounded border border-green-500">お気に入り登録済みです</span>
            @else
                <form action="{{ route('spot.addToFavorites', $spot->id) }}" method="POST" class="inline-block mr-2">
                    @csrf
                    <button type="submit" class="btn-blue px-4 py-2 rounded">お気に入り登録</button>
                </form>
            @endif
            
            <!-- 編集ボタン -->
            @if(Auth::id() === $spot->user_id)
                <a href="{{ route('spot.edit', $spot->id) }}" class="btn-green px-4 py-2 rounded mr-2">編集</a>
            
                <!-- 削除ボタン -->
                <form action="{{ route('spot.destroy', $spot->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-red px-4 py-2 rounded">削除</button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- モーダルの定義 -->
<div id="modal" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-8 max-w-3xl">
        <img id="modalImage" src="" alt="" class="rounded-lg shadow-md cursor-pointer" onclick="toggleModal()">
    </div>
</div>

<!-- JavaScript の追加 -->
<script>
    function toggleModal(imageUrl) {
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modalImage');
        
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modalImage.src = imageUrl;
        } else {
            modal.classList.add('hidden');
            modalImage.src = '';
        }
    }
</script>
@endsection
