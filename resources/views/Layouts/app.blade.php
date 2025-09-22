<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ helper_tituloPagina() }} | {{ $headTitle }}</title>
    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="{{ URL::to('/') }}/public/favicon.ico">
    <!-- Token para formularios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('Layouts.dependenciesCSS')

</head>

<body data-bs-theme="dark" class="d-flex flex-column min-vh-100">

    @include('Layouts.header')

    <main class="flex-grow-1">
        <div class="container">
            @yield('content')
        </div>
    </main>
    
    @include('Layouts.modalSignOut')

    @include('Layouts.footer')

    @include('Layouts.dependenciesJS')

    @yield('scripts')
</body>

</html>
