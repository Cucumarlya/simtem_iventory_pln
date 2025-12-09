<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js', 'resources/js/login.js'])
    </head>
    <body class="antialiased" style="margin:0; min-height:100vh; display:flex; justify-content:center; align-items:center; background:linear-gradient(135deg, #1d2b64, #4facfe); font-family:'Segoe UI', sans-serif;">
        
        <div>
            {{ $slot }}
        </div>

    </body>
</html>
