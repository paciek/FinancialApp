<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Financial APP')</title>
    @include('partials.frontend-assets')
</head>
<body class="@yield('body_class', 'bg-light')">
    @yield('content')
</body>
</html>
