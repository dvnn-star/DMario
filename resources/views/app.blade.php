<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        @routes
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Dmario') }}</title>

        {{-- ===== SEO Meta Tags (Server-Side for Social Bots) ===== --}}
        @php
            $seo = $page['props']['seo'] ?? [];
            $appName = config('app.name', 'Dmario');
            $appUrl = rtrim(config('app.url'), '/');
        @endphp

        {{-- Meta Description --}}
        @if(!empty($seo['description']))
            <meta name="description" content="{{ $seo['description'] }}">
        @else
            <meta name="description" content="D'Mario Sunset Resto & Cafe — Destinasi kuliner terbaik untuk menikmati sunset dan hidangan lezat di Tanjung Uban, Bintan.">
        @endif

        {{-- Robots --}}
        @if(!empty($seo['robots']))
            <meta name="robots" content="{{ $seo['robots'] }}">
        @endif

        {{-- Canonical URL --}}
        @if(!empty($seo['canonical']))
            <link rel="canonical" href="{{ $seo['canonical'] }}">
        @endif

        {{-- Open Graph --}}
        <meta property="og:site_name" content="{{ $appName }}">
        <meta property="og:locale" content="id_ID">
        @if(!empty($seo['title']))
            <meta property="og:title" content="{{ $seo['title'] }}">
        @else
            <meta property="og:title" content="{{ $appName }} — Sunset Resto & Cafe">
        @endif
        @if(!empty($seo['description']))
            <meta property="og:description" content="{{ $seo['description'] }}">
        @else
            <meta property="og:description" content="D'Mario Sunset Resto & Cafe — Destinasi kuliner terbaik untuk menikmati sunset dan hidangan lezat di Tanjung Uban, Bintan.">
        @endif
        <meta property="og:type" content="{{ $seo['ogType'] ?? 'website' }}">
        @if(!empty($seo['canonical']))
            <meta property="og:url" content="{{ $seo['canonical'] }}">
        @else
            <meta property="og:url" content="{{ $appUrl }}">
        @endif
        @if(!empty($seo['ogImage']))
            <meta property="og:image" content="{{ $seo['ogImage'] }}">
        @else
            <meta property="og:image" content="{{ $appUrl }}/dmario.jpeg">
        @endif

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        @if(!empty($seo['title']))
            <meta name="twitter:title" content="{{ $seo['title'] }}">
        @else
            <meta name="twitter:title" content="{{ $appName }} — Sunset Resto & Cafe">
        @endif
        @if(!empty($seo['description']))
            <meta name="twitter:description" content="{{ $seo['description'] }}">
        @else
            <meta name="twitter:description" content="D'Mario Sunset Resto & Cafe — Destinasi kuliner terbaik untuk menikmati sunset dan hidangan lezat di Tanjung Uban, Bintan.">
        @endif
        @if(!empty($seo['ogImage']))
            <meta name="twitter:image" content="{{ $seo['ogImage'] }}">
        @else
            <meta name="twitter:image" content="{{ $appUrl }}/dmario.jpeg">
        @endif
        {{-- ===== End SEO Meta Tags ===== --}}

        {{-- Theme Color --}}
        <meta name="theme-color" content="#0a0a0b">

        {{-- Favicons --}}
        <link rel="icon" href="/dmario.jpeg" type="image/jpeg">
        <link rel="apple-touch-icon" href="/dmario.jpeg">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
