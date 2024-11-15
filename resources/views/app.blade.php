<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/js/app.js') <!-- Assurez-vous que Vite est bien configuré -->
    @vite('resources/css/app.css')
    
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"> 
    <!-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> -->
</head>
<body>
    @inertia
</body>
</html>