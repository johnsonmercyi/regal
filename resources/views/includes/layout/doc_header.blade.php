<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', "Welcome to TST School CMS")</title>

    <!-- Fonts -->
    <!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">-->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap" rel="stylesheet"> -->

    <!-- Materialize Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/material-icons.css') }}">

    <!-- Materialize Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialize.min.css') }}" media="screen,projection" />
    <link rel="stylesheet" href="{{ asset('assets/css/signature-pad.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ie9.css') }}">


        {{-- Styles for the data table --}}
    {{-- <link rel="stylesheet" href=" {{ asset('assets/DataTables/datatables.min.css') }}"> --}}
    <link rel="stylesheet" href=" {{ asset('assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css') }}">
        {{-- <link rel="stylesheet" href="assets/css/material.min.css">
        <link rel="stylesheet" href="assets/css/dataTables.material.min.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/DataTables/Select-1.3.0/css/select.dataTables.min.css') }}"> --}}

        <!-- Custom Css -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/nursery-result.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/shw_style.css') }}" />
    

    
    <style>

        .schoolcol{
            background-color: {{ $school->themeColor ?: 'blue' }};
        }

        .verticalTableHeader {
            /* text-align:center; */
            max-width:10px;
            font-size:12px;
            /* white-space:nowrap; */
            /* transform-origin:50% 50%; */
            transform: rotate(90deg);
            /* vertical-align:baseline; */
            /* padding-left:0 !important;
            padding-right:10px !important; */
        }

        .subjectsDiv {
            /*-ms-transform: rotate(-90deg); /* IE 9 */
            /*-webkit-transform: rotate(-90deg); /* Chrome, Safari, Opera */
            transform: rotate(-90deg);
            width:100px;
            height:100%;
            text-align:left;
            padding:0px;
            /* background:#FFF; */
            
        }
        .subjectsDivTd {
        
            max-width:30px;
            min-width:30px;
            border:1px solid;
            overflow:hidden;
            margin:0px;
            padding:0px;
            background:#000;
        }

        .numInput{
            width:50px !important;
            background-color:#ddd !important;
            border-radius:5px !important;
        }
            
        #video {
        border: 1px solid black;
        /* box-shadow: 2px 2px 3px black; */
        width:180px;
        height:140px;
        }

        .colCode{
            background-color: {{ $school->themeColor ?: 'blue'}}
        }

        .colCode:hover{
            background-color: {{ $school->themeColor ?: 'blue'}}
        }

        .colCode:active{
            background-color: {{ $school->themeColor ?: 'blue'}}
        }

        #photo {
        border: 1px solid black;
        /* box-shadow: 2px 2px 3px black; */
        width:180px;
        height:140px;
        }

        #canvas {
        display:none;
        }

        /* .camera {
        width: 180px;
        display:inline-block;
        } */

        .output {
        width: 180px;
        display:inline-block;
        }

        .borderRound{
            border:0.1rem solid {{$school->themeColor}};
        }

        .borderBot{
            border-bottom:0.1rem solid {{$school->themeColor}};
        }
        .rightleftbord{
            border-left: 0.2rem solid {{$school->themeColor}};
            border-right: 0.2rem solid {{$school->themeColor}};
        }
        .leftBord{
            border-left: .3rem solid {{$school->themeColor}};
            padding-left: .5rem;
        }
    </style>

</head>
