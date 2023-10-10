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

    <script src="{{ asset('assets/js/jquery/jquery-3.4.1.min.js') }}"></script>

    <script src="{{ asset('assets/js/materialize.min.js') }}"></script>

        <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    
    <style>
        
        .borderRound{
            border:0.1rem solid {{$school->themeColor}};
        }

        .borderBot{
            border-bottom:0.1rem solid {{$school->themeColor}};
        }

        #progressModal{
            top: 40% !important;
        }
    </style>

</head>


    <body class="">

        <!-- Includes the HEADER section of the body -->
        {{--@include('schools.inc.school')--}}

        @include('schools.inc.studentSidebar')
        <input type="hidden" id="schoolId" value="{{ $school->id}}">

        <div class="main-container padSide ">

            @yield('content')
        </div>

        <div id="progressModal" class="modal" >
            <div class="modal-content">
                <div class="progress hide">
                    <div class="indeterminate {{$school->themeColor}}"></div>
                </div>
            </div>
        </div>
        <!-- Includes the FOOTER section of the body -->
        @include('includes.layout.body_footer')


        <script src="{{ asset('assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/materialize.min.js') }}"></script>
        <script src="{{ asset('assets/util/layoutManager.js') }}" ></script>
        <script type="module" src="{{ asset('assets/custom/layout.js') }}"></script>
        
<script>
    $(document).ready(function(){
    $('.sidenav').sidenav();
  });
</script>


    </body>
</html>