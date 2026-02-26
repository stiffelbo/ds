{{-- resources/views/layouts/app.blade.php --}}

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ session('ui.theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Laravel DS'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-100 text-base-content">

{{-- Navbar --}}
<div class="navbar bg-base-200 border-b border-base-300">
    <div class="flex-1">
        <a href="/" class="btn btn-ghost text-lg font-semibold">
            Laravel DS
        </a>
    </div>

    <div class="flex-none">
        {{-- Theme switcher route('ui.theme.set')--}}
        <form method="POST" action="">
            @csrf
            <select name="theme"
                    onchange="this.form.submit()"
                    class="select select-bordered select-sm">
                @php
                    $current = session('ui.theme', 'light');
                    $themes = ['light','dark','cupcake','corporate','business','night'];
                @endphp

                @foreach($themes as $theme)
                    <option value="{{ $theme }}"
                        @selected($current === $theme)>
                        {{ ucfirst($theme) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- Main content --}}
<main class="py-8">
    @yield('content')
</main>

</body>
</html>
