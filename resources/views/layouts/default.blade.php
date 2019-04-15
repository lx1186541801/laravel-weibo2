<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'WEIBO App')</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="/" class="navbar-brand">WEIBO App</a>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item"><a href=" {{ route('help') }}">HELP</a></li>
                <li class="nav-item"><a href=" {{ route('help') }}">LOGIN</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>