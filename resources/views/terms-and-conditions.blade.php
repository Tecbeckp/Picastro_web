<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable" data-theme="default" data-bs-theme="dark"
      data-theme-colors="default">

<head>
    <meta charset="utf-8"/>
    <title>Terms of Service | Picastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('includes.style')
</head>

<body>
<div class="auth-page-wrapper pt-5">
    @include('flash-message')

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <!-- Terms and Conditions Content -->
            <div class="row justify-content-center">
                <div class="col-lg-12">{!!$terms->content!!}</div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->
</div>
<!-- end auth-page-wrapper -->

<!-- JAVASCRIPT -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>

<!-- particles js -->
<script src="assets/libs/particles.js/particles.js"></script>
<!-- particles app js -->
<script src="assets/js/pages/particles.app.js"></script>
<!-- password-addon init -->
<script src="assets/js/pages/password-addon.init.js"></script>
</body>
</html>
