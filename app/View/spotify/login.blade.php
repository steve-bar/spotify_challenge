<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Login</title>
</head>
<body>
    <div class="container">
        <h1>Spotify Login</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="remember">Remember me:</label>
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>

            <a href="{{ route('forgot-password') }}" class="float-right">Forgot your password?</a>
        </form>

        <p>Or login with Spotify:</p>

        <a href="{{ route('spotify.login') }}" class="btn btn-primary">Login with Spotify</a>
    </div>
</body>
</html>
