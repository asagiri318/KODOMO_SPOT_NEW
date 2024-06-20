@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold">プロフィール編集</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="mt-5">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">名前</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required autofocus>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">更新する</button>
        </div>
    </form>
</div>
@endsection
