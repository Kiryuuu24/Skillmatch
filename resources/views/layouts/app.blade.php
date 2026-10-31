<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SkillMatch') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
      h1, h2, h3, .font-heading { font-family: 'Bricolage Grotesque', ui-serif, Georgia, serif; }
    </style>
</head>
<body class="bg-white text-[#1A1A2E]" id="app">
    <div class="min-h-screen flex flex-col">
        @yield('content')
    </div>
</body>
</html>
