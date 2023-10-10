<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Includes this document header -->
    @include('includes.layout.doc_header')

    <body class="grey lighten-5">

        <!-- Includes the HEADER section of the body -->
        @include('includes.layout.body_header')

        <!-- Includes the MAIN section of the body -->
        @include('includes.layout.body_main')

        <!-- Includes the FOOTER section of the body -->
        @include('includes.layout.body_footer')


        <!-- Includes the SCRIPT IMPORT section of the body -->
        @include('includes.layout.script_imports')

    </body>
</html>
