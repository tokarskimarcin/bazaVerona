<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
    @include('partials._style')
    @yield('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    @include('partials._nav')

    <div class="container">

        @yield('content')

        @include('partials._footer')

    </div> <!-- end of .container -->

        @include('partials._javascript')
        @yield('script')
</body>

</html>