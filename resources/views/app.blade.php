<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark bg-gray-200 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <!-- Browser icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png"/>
    <link rel="manifest" href="/manifest.json"/>
    <link rel="shortcut icon" href="/assets/favicons/favicon.ico"/>
    <meta name="theme-color" content="#4e64b7"/>

    <!-- Scripts -->
    @routes

    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="font-sans h-full">
@inertia
</body>
</html>
