<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loom Template Designer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    {{--
        Tailwind CDN safelist — these classes are generated dynamically and must
        appear somewhere verbatim so the scanner includes them:
        bg-blue-600 bg-blue-700 bg-indigo-50 bg-indigo-100 bg-indigo-600
        bg-gray-50 bg-gray-100 bg-gray-200 bg-gray-300 bg-gray-400
        bg-gray-700 bg-gray-800 bg-gray-900
        bg-white bg-amber-100
        border-blue-400 border-blue-500 border-indigo-300
        border-gray-200 border-gray-300 border-gray-600 border-gray-700
        text-blue-600 text-gray-400 text-gray-500 text-gray-700 text-gray-900
        text-white text-xs text-sm text-indigo-700
        ring-2 ring-blue-400 ring-offset-1
        font-mono font-semibold font-medium
        opacity-50 opacity-100
        w-48 w-56 w-64 w-72 w-80
        flex-1 flex-shrink-0
        overflow-y-auto overflow-x-hidden
        cursor-grab cursor-grabbing cursor-pointer
        rounded rounded-md rounded-sm
        px-2 px-3 px-4 py-1 py-2 py-3
        gap-1 gap-2
        space-y-1 space-y-2 space-y-3
        min-h-12 min-h-16 min-h-20
        select-none
        shadow shadow-sm shadow-md
        transition-colors duration-100
        col-span-2
    --}}
</head>
<body class="h-full overflow-hidden">
    {{ $slot }}
    @livewireScripts
</body>
</html>
