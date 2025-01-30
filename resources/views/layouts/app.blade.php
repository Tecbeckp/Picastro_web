<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:url" content="http://picastro.co.uk/" />
    <meta property="og:image" content="{{ asset('assets/images/picastro.png') }}" />
    @include('includes.style')
    @include('includes.dtstyle')
    @stack('style')
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('flash-message')

        @include('includes.header')

        <!-- ========== App Menu ========== -->
        @include('includes.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('content')

            <!-- End Page-content -->

            @include('includes.footer')

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    @include('includes.dtscript')
    @include('includes.script')
    @stack('script')
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'u') {
                event.preventDefault();
                console.log("Viewing source is disabled!");
            }
        });
    </script>
</body>


</html>
