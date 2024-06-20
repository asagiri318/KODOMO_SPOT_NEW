<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>

        <div>
            <button type="submit">ログイン</button>
        </div>
    </form>

    <a href="{{ route('password.request') }}">パスワードをお忘れですか？</a>
    <br>
    <a href="{{ route('register') }}">新規登録はこちら</a>
</body>
</html>
