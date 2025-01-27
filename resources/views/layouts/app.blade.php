@props([
    'title' => null,
])
<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        dir="{{ __('filament::layout.direction') ?? 'ltr' }}"
        class="filament js-focus-visible min-h-screen antialiased"
>
<head>
    {{ \Filament\Facades\Filament::renderHook('head.start') }}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @foreach (\Filament\Facades\Filament::getMeta() as $tag)
        {{ $tag }}
    @endforeach

    @if ($favicon = config('filament.favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @endif

    <title>{{ $title ? "{$title} - " : null }} {{ config('filament.brand') }}</title>

    {{ \Filament\Facades\Filament::renderHook('styles.start') }}

    <style>
        [x-cloak=""], [x-cloak="x-cloak"], [x-cloak="1"] { display: none !important; }
        @media (max-width: 1023px) { [x-cloak="-lg"] { display: none !important; } }
        @media (min-width: 1024px) { [x-cloak="lg"] { display: none !important; } }
        :root {
            --sidebar-width: {{ config('filament.layout.sidebar.width') ?? '20rem' }};
            --collapsed-sidebar-width: {{ config('filament.layout.sidebar.collapsed_width') ?? '5.4rem' }};
        }
    </style>

    @livewireStyles

    @if (filled($fontsUrl = config('filament.google_fonts')))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $fontsUrl }}" rel="stylesheet" />
    @endif

    @foreach (\Filament\Facades\Filament::getStyles() as $name => $path)
        @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
            <link rel="stylesheet" href="{{ $path }}" />
        @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
            {!! $path !!}
        @else
            <link rel="stylesheet" href="{{ route('filament.asset', [
                    'file' => "{$name}.css",
                ]) }}" />
        @endif
    @endforeach

    {{ \Filament\Facades\Filament::getThemeLink() }}

    {{ \Filament\Facades\Filament::renderHook('styles.end') }}

    @if (config('filament.dark_mode'))
        <script>
            const theme = localStorage.getItem('theme')

            if ((theme === 'dark') || (! theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            }
        </script>
    @endif

    {{ \Filament\Facades\Filament::renderHook('head.end') }}
</head>

<body @class([
        'filament-body min-h-screen bg-gray-100 text-gray-900 overflow-y-auto',
        'dark:text-gray-100 dark:bg-gray-900' => config('filament.dark_mode'),
    ])>
{{ \Filament\Facades\Filament::renderHook('scripts.end') }}

{{ \Filament\Facades\Filament::renderHook('body.end') }}
</body>
</html>