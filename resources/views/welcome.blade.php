<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>Post | Picastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use Illuminate\Support\Facades\DB;
        $id = request('id');
        $results = DB::table('post_images')->where('id', base64_decode($id))->first();
       
    @endphp
    @if ($results)
        <meta property="og:url" content="https://picastro.co.uk/post/{{$id}}" />
        <meta property="og:image" content="{{ asset(json_decode($results->image)[0]) }}" />
        <meta property="og:type" content="Post Image" />
        <meta property="og:title" content="{{ $results->post_image_title ?? $results->catalogue_number }}" />
        <meta property="og:description" content="{{ $results->description }}" />
    @endif

    @include('includes.style')
    <style>
        .btn-theme-red{
            background: #ED1C24;
            border-color: #ED1C24;
        }
        .auth-bg-cover {
            background: linear-gradient(-45deg, #4d2425 50%, #333333);
        }
        .w-50{
            width: 45% !important;
            border-radius: 10px 
        }
        .card-body.p-5{
            padding: 75px 30px !important;
            border-radius: 8px;
        }
    </style>

</head>

<body>

    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                            <div class="card-body p-5">
                                <div class="text-center">
                                    <img src="{{ asset('assets/images/picastro.png') }}" alt="" style="width: 50%;">
                                    <h1 class="text-white mb-4 mt-4">Download Picastro App</h1>
                                    {{-- <h4 class="text-uppercase">Sorry, Page not Found ðŸ˜­</h4> --}}
                                    <p class="text-white mb-4">Download the app today using the links below</p>
                                    <a target="_blank" href="https://apps.apple.com/pk/app/picastro/id6446713728"><img src="{{asset('assets/images/app_store.png')}}" alt="Playstore" class="w-50"></a>
                                    <a target="_blank" href="https://play.google.com/store/search?q=picastro&c=apps&hl=en"><img src="{{asset('assets/images/Google-Play-Store-Logo-PNG-Transparent.png')}}" alt="Playstore" style="width: 49% !important;border-radius: 10px"></a>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
    </div>

    @include('includes.script')
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