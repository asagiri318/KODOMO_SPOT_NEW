<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Adjust the height for full viewport height including mobile browsers */
        body {
            height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
        <h1 class="text-center text-2xl font-bold mb-6">ユーザー登録</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 border border-red-200 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="nickname" class="block mb-1">ニックネーム</label>
                <input id="nickname" type="text" name="nickname" value="{{ old('nickname') }}" required autofocus class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-1">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-1">パスワード（8文字以上）</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block mb-1">パスワード確認</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full p-2 bg-green-500 text-white rounded hover:bg-green-600">登録</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="block text-blue-600 hover:underline">すでに登録済みの方はログイン</a>
        </div>
    </div>
</body>
</html>
