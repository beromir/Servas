<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Swagger</title>

    <!-- Scripts -->
    @vite(['resources/js/swagger/swagger.js'])
</head>
<body>
<main>
    <div id="swagger"></div>
</main>
</body>
</html>
