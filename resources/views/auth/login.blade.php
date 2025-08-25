<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Login</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="container">
        <div class="box">
            <span class="borderLine"></span>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login', app()->getLocale()) }}">
                @csrf
                <h2>Sign in</h2>
                
                <!-- Email -->
                <div class="inputBox">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    <span>Email</span>
                    <i></i>
                </div>
                
                <!-- Password -->
                <div class="inputBox">
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    <span>Password</span>
                    <i></i>
                </div>
                
              
                
                <input type="submit" id="submit" value="{{ __('Log in') }}">
            </form>
        </div>
    </div>
</body>
</html>
