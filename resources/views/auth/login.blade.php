<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center bg-gray-100 min-h-screen">

    <div class="w-full max-w-md mx-4">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-800">スポシェア</h1>
            <p>〜お出かけスポット保存・共有アプリ〜</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-center text-2xl font-bold mb-6">ログイン</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 border border-red-400 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block mb-2">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-2">パスワード（8文字以上）</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">ログイン</button>
                </div>
            </form>

            <div class="text-center">
                <a href="{{ route('password.request') }}" class="block text-blue-500 hover:underline mb-2">パスワードをお忘れですか？</a>
                <a href="{{ route('register') }}" class="block text-blue-500 hover:underline mb-2">新規登録はこちら</a>
                <p>※初めての方はまずご登録ください♪</p>
            </div>
        </div>
    </div>

</body>
</html>
