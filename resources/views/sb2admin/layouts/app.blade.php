<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - TASK MANAGEMENT</title>

    {{-- Styles --}}
    @include('sb2admin.partials.styles')

    {{-- Slot tambahan untuk CSS khusus per halaman --}}
    @stack('styles')
</head>

<body class="sb-nav-fixed">

    @include('sb2admin.partials.topbar')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('sb2admin.partials.sidebar')
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    @yield('content')

                </div>
            </main>

            @include('sb2admin.partials.footer')
        </div>
    </div>

    {{-- Scripts --}}
    @include('sb2admin.partials.scripts')

</body>

</html>
