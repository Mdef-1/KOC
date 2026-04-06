<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'KOC - Premium Unisex Sportswear' }}</title>

    {{-- Google Fonts - KOC Brand --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire --}}
    @livewireStyles

    {{-- External SDK --}}
    <script src="/_sdk/element_sdk.js" defer></script>
    <script src="/_sdk/data_sdk.js" defer></script>
</head>

<body class="h-full smooth-scroll overflow-auto">

    @livewire('navbar')
    <div class="h-16 sm:h-20"></div>
    {{-- Page Content --}}
    {{ $slot }}
    {{-- Livewire --}}
    @livewireScripts
</body>
</html>
