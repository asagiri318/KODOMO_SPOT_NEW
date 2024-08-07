<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
        }
        .title {
            position: absolute;
            top: 5px;
            text-align: center;
            width: 100%;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-top: 80px; /* 上部の余白 */
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .links {
            text-align: center;
            margin-top: 10px;
        }
        .links a {
            display: block;
            margin-bottom: 5px;
            color: #0066cc;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        /* スマホ用の余白設定 */
        @media (max-width: 600px) {
            .login-container {
                padding: 30px;
                margin: 0 20px;
            }
            .title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="title"><h1>スポシェア</h1>
        〜お出かけスポット保存・共有アプリ〜<br>
    </div>

    <div class="login-container">
        <h1>ログイン</h1>

        @if ($errors->any())
            <div class="error-messages">
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
                <label for="password">パスワード（8文字以上）</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <div>
                <button type="submit">ログイン</button>
            </div>
        </form>

        <div class="links">
            <a href="{{ route('password.request') }}">パスワードをお忘れですか？</a>
            <a href="{{ route('register') }}">新規登録はこちら</a><br>
            ※初めての方はご登録を♪
        </div>
    </div>
</body>
</html>
