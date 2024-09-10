<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
	<i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!-- JAVASCRIPT -->
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{asset('assets/js/plugins.js')}}"></script>
<script>
	(document.querySelectorAll("[toast-list]")||
document.querySelectorAll("[data-choices]")||
document.querySelectorAll("[data-provider]"))&&
(document.writeln('<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"><\/script>'),
document.writeln('<script type="text/javascript" src="{{asset("assets/libs/choices.js/public/assets/scripts/choices.min.js")}}"><\/script>'),
document.writeln('<script type="text/javascript" src="{{asset("assets/libs/flatpickr/flatpickr.min.js")}}"><\/script>'));
	</script>
<!-- apexcharts -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- projects js -->
<script src="{{asset('assets/js/pages/dashboard-projects.init.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/js/app.js')}}"></script>