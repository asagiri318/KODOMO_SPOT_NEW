<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードリセット</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
        <h1 class="text-center text-2xl mb-6">パスワードリセット</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 border border-green-200 p-4 rounded mb-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block mb-2">メールアドレス</label>
                <input id="email" type="email" class="w-full p-2 border border-gray-300 rounded" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600">パスワードリセットリンクを送信する</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">ログイン画面に戻る</a>
        </div>
    </div>
</body>
</html>
