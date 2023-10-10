<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Includes this document header -->
    @include('includes.layout.doc_header')

    <body class="">
        <!-- PRELOADER -->
        <div id='preloaderCon'>
            <div class='preloader-wrapper big active'>
                <div class='spinner-layer spinner-blue-only'>
                    <div class='circle-clipper left'>
                        <div class='circle'></div>
                    </div>
                    <div class='gap-patch'>
                        <div class='circle'></div>
                    </div>
                    <div class='circle-clipper right'>
                        <div class='circle'></div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Includes the HEADER section of the body -->
        @include('schools.inc.school')
        
        <!-- Includes the MAIN section of the body -->
        @include('includes.layout.body_main')

        <!-- Includes the FOOTER section of the body -->
        @include('includes.layout.body_footer')

        <div id="progressModal" class="modal">
            <div class="modal-content">
                <div class="progress hide">
                    <div class="indeterminate {{$school->themeColor}}"></div>
                </div>
            </div>
        </div>
        <!-- Includes the SCRIPT IMPORT section of the body -->
        @include('includes.layout.script_imports')

    </body>
</html>