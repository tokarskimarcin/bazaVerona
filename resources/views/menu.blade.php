<!DOCTYPE html>
<html lang="en">

    @include('partials._head')

<body style="background: #000;">

<div class="container-fluid multiBanner">

    @yield('content')

    @include('partials._footer')

</div> <!-- end of .container -->


    @include('partials._javascript')
</body>

</html>