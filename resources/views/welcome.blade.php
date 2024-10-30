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
        <meta property="og:image" content="{{ asset($results->image) }}" />
        <meta property="og:type" content="Post Image" />
        <meta property="og:title" content="{{ $results->post_image_title ?? $results->catalogue_number }}" />
        <meta property="og:description" content="{{ $results->description }}" />
    @endif

    @include('includes.style')


</head>

<body>

    <div class="container-fluid">

        <!-- Forgot Password Email -->
        <div class="row">

            <div class="col-12 mt-3">
                <h1>Download Picastro app to see this post</h1>
                <!-- end table -->
            </div>
        </div>
        <!-- end row -->

        <!--end row-->

    </div>
    <!-- container-fluid -->


    @include('includes.script')
</body>

</html>
