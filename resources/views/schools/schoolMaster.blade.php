<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Schools View')</title>
    {{--<link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('assets/css/materialize.min.css') }}" media="screen,projection" />
    <link rel="stylesheet" href="{{ asset('assets/css/schools.css') }}" media="screen,projection" />
    {{--<link rel="stylesheet" href="{{ asset('css/schools.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('fonts/material-icons.css') }}">
</head>
<body>
        <nav>
            <div class="nav-wrapper">
                <!--a href="#!" class="brand-logo">Logo</a-->
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <form class="col s5 right">
                    <div class="input-field">
                        <input id="search" type="search" required class="autocomplete" onkeyup="onKeyUp()">
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
                <ul class="left hide-on-med-and-down">
                    <li><a href="/schools">All School List</a></li>
                </ul>
            </div>
        </nav>
            
        <ul class="sidenav" id="mobile-demo">
            <li><a href="/schools">Home</a></li>
        </ul>
    
        
        
    <div class="container">
        @yield('contents')
    </div>
<script src="{{ asset('assets/js/materialize.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('assets/js/schoolsCreate.js') }}"></script>
    
    {{--<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>--}}
    {{--<script src="{{ asset('js/materialize.js') }}"></script>--}}
    {{--<script src="{{ asset('js/schools.js') }}"></script>--}}
</body>
</html>