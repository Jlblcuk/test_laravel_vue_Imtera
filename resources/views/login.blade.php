<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Daily Grow</title>
    @vite(['resources/css/login.css'])
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <img src="{{ Vite::asset('resources/images/logo.jpg') }}" class="logo-img">
            <h1>Daily Grow</h1>
        </div>

        <h2>Вход в систему</h2>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    required
                    autofocus
                />
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                />
            </div>

            <button type="submit">Войти</button>
        </form>

        <div class="hint">
            <p>Данные для входа:</p>
            <p><strong>Email:</strong> admin@example.com</p>
            <p><strong>Пароль:</strong> password</p>
        </div>
    </div>
</body>
</html>
