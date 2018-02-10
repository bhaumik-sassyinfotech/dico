<!DOCTYPE html>
<html>
    <head>
        @include('front_template.head')
    </head>
    <body id="content-body">
        
        <!-- ************* Header-Section ********************-->
        <div class="header-wrapper">
            @include('front_template.header')
        </div>
        <!-- End header Section -->

        <!-----header welcome section-------->
        @yield('content')
        <!-- End Content Section -->

        <!-- ************* footer-Section ********************-->
        <div class="footer-wrapper">
            @include('front_template.footer')	
        </div>
        <!-- ************* Footer-Section ********************-->

        <!-- End Main Wrapper -->
        @include('front_template.javascripts')
    </body>
</html>