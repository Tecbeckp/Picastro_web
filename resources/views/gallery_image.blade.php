<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>Gallery Image | Picastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        use Illuminate\Support\Facades\DB;
        $id = request('id');
    @endphp
    <meta property="og:url" content="{{ url('profile/' . $id) }}" />
    <meta property="og:image" content="{{ $user->userprofile->profile_image }}" />
    <meta property="og:type" content="User Profile" />
    <meta property="og:title" content="{{ $user->username }}" />
    <meta property="og:description"
        content="{{ $user->userprofile->bio ?? $user->userprofile->first_name . ' ' . $user->userprofile->last_name }}" />

    @include('includes.style')
    <link href="{{ asset('assets/app.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <style>
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
    </style> --}}
    <style>
        body {
            background-color: black !important;
        }

        .profile-card {
            background-color: #2f2f2f;
            color: white;
            border-radius: 12px;
            padding: 20px;
            width: 100%;
            margin: auto;
        }

        .profile-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2px solid #ffcc00;
            object-fit: cover;
        }

        .profile-card .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
        }

        .profile-card .stats div {
            text-align: center;
        }

        .profile-card .links a {
            display: block;
            color: #6c757d;
            font-size: 12px;
            text-decoration: none;
        }

        .profile-card .btn-follow {
            background-color: #ffd700;
            color: #1c1c1c;
            font-weight: bold;
            border: none;
            margin-top: 15px;
        }

        .profile-card .badges {
            display: flex;
            justify-content: space-evenly;
            margin-top: 15px;
        }

        .profile-card .badges img {
            width: 25px;
            height: 25px;
        }

        .main-content-app .card-footer {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .card-footer i {
            position: static !important;
            font-size: 22px !important;
        }

        .truncate {
            display: inline-block;
            max-width: 100%;
            /* Adjust based on parent container */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>

    <div class="main-content-app overflow-hidden">

        <div class="page-content">
            <div class="container-fluid">
                <!-- App Listing -->
                <div class="row">
                    @forelse ($posts as $item)
                        <div class="col-6 col-sm-6" style="padding-bottom: 7px !important;">
                            <div class="card small-card mb-0">
                                <div class="card-body p-0">
                                    <img src="{{ $item['original_image'] }}" alt="Space Image" class="spaceImg">
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        </div>
        <!-- end main content-->

    </div>

    @include('includes.script')
    <script>
        // Disable right-click context menu for the entire page
        document.addEventListener('contextmenu', (event) => event.preventDefault());
    
        // Disable dragging on all images
        document.addEventListener('dragstart', (event) => {
            if (event.target.tagName.toLowerCase() === 'img') {
                event.preventDefault();
            }
        });
    
        // Optional: Disable specific keyboard shortcuts for inspecting
        document.addEventListener('keydown', (event) => {
            // Prevent Ctrl+Shift+I (DevTools in most browsers)
            if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key === 'I') {
                event.preventDefault();
            }
            // Prevent F12 key
            if (event.key === 'F12') {
                event.preventDefault();
            }
        });
    </script>    
</body>

</html>
