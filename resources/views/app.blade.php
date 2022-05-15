<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Browser icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png"/>
    <link rel="manifest" href="/manifest.json"/>
    <link rel="shortcut icon" href="/assets/favicons/favicon.ico"/>
    <meta name="theme-color" content="#4e64b7"/>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="font-sans h-full">
@inertia
</body>
</html>
