<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', "")</title>

    <!-- Fonts -->
    <!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">-->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap" rel="stylesheet"> -->

    <!-- Materialize Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/material-icons.css') }}">

    <!-- Materialize Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialize.min.css') }}" media="screen,projection" />

    <script src="{{ asset('assets/js/materialize.min.js') }}"></script>

        <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />


</head>


    <body class="grey lighten-5">

        <div class="container">

            @yield('content')
        </div>
        <!-- Includes the FOOTER section of the body -->
        @include('includes.layout.body_footer')

    </body>
</html>