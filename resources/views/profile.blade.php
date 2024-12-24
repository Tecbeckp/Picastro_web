<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>User Profile | Picastro</title>
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
    {{-- <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
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
                                    <p class="text-white mb-4">Download the app today using the links below</p>
                                    <a target="_blank" href="https://apps.apple.com/pk/app/picastro/id6446713728"><img src="{{asset('assets/images/app_store.png')}}" alt="Playstore"  class="w-50"></a>
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
    </div> --}}

    <div class="main-content-app overflow-hidden">

        <div class="page-content">
            <div class="container-fluid">
                <!-- App Listing -->
                <div class="row">
                    <div class="col-12 col-sm-12" style="padding: 0;padding-bottom: 10px !important;">
                        <div class="profile-card small-card mb-0">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->userprofile->profile_image }}" alt="Profile Picture">
                                <div class="ms-3">
                                    <h5 class="mb-0">{{ $user->username }}</h5>
                                    <small class="text-muted">{{ $user->userprofile->pronouns }}</small>
                                </div>
                                <span class="ms-auto text-danger" style="font-size: large;">★ <span
                                        style="color: #fff !important;font-size: medium;">{{ number_format($user->total_star_count) }}</span></span>
                            </div>

                            <div class="stats mt-3">
                                <div>
                                    <span class="fw-bold">{{ $posts->count() }}</span>
                                    <div>Posts</div>
                                </div>
                                <div>
                                    <span class="fw-bold">{{ $user->userprofile->followers }}</span>
                                    <div>Followers</div>
                                </div>
                                <div>
                                    <span class="fw-bold">{{ $user->userprofile->following }}</span>
                                    <div>Following</div>
                                </div>
                            </div>

                            <p class="mt-3">{{ $user->userprofile->bio }}</p>
                            <div class="links mt-2 d-flex gap-3">
                                <a href="{{ Str::startsWith($user->userprofile->web_site_one, ['http://', 'https://'])
                                    ? $user->userprofile->web_site_one
                                    : 'https://' . $user->userprofile->web_site_one }}"
                                    target="_blank" class="truncate">
                                    {{ $user->userprofile->web_site_one }}
                                </a>
                                <a href="{{ Str::startsWith($user->userprofile->web_site_two, ['http://', 'https://'])
                                    ? $user->userprofile->web_site_two
                                    : 'https://' . $user->userprofile->web_site_two }}"
                                    target="_blank" class="truncate">
                                    {{ $user->userprofile->web_site_two }}
                                </a>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div class="badges d-flex gap-2 mt-3" style="gap: 1rem !important;">
                                    @forelse ($trophies as $key => $item)
                                        <span><img src="{{ asset($item->icon) }}" alt="Badge 1"
                                                style="border: none !important;height: auto !important;width: auto !important;border-radius: 0px !important;">&nbsp;{{ $vote[$key + 1] }}</span>
                                    @empty
                                    @endforelse
                                </div>

                                {{-- <button class="btn btn-follow">Follow</button> --}}
                            </div>
                        </div>
                    </div>
                    @forelse ($posts as $item)
                        <div class="col-6 col-sm-4" style="padding-bottom: 7px !important;">
                            <div class="card small-card mb-0">
                                <div class="card-body p-0">
                                    <img src="{{ $item['image'] }}" alt="Space Image" class="spaceImg">
                                    <div class="card-footer">
                                        @if ($item['post_image_title'])
                                            <p class="mb-0">{{ $item['post_image_title'] }}
                                            </p>
                                        @elseif($item['catalogue_number'])
                                            <p class="mb-0">{{ $item['catalogue_number'] }}
                                                <br><span class="fs-12">{{ $item['object_name'] }}</span>
                                            </p>
                                        @elseif(isset($item['ObjectType']))
                                            <p class="mb-0">{{ $item['ObjectType']->name }}
                                                <br><span class="fs-12">{{ $item['object_name'] }}</span>
                                            </p>
                                        @endif

                                        {{-- <i class="bx bx-star"></i> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    <div class="col-12 col-sm-12" style="padding: 0">
                        <div class="profile-card small-card mb-0"
                            style="padding: 0 !important;margin-top: 10px;background: none;">
                            <div class="d-flex align-items-center">
                                <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                                    <div class="card-body p-5">
                                        <div class="text-center">
                                            <img src="{{ asset('assets/images/picastro.png') }}" class="profile"
                                                alt=""
                                                style="width: 50%;border: none;height: auto;border-radius: unset;">
                                            <h1 class="text-white mb-4 mt-4">Download Picastro App</h1>
                                            <p class="text-white mb-4">Download the app today using the links below
                                            </p>
                                            <a target="_blank"
                                                href="https://apps.apple.com/pk/app/picastro/id6446713728"><img
                                                    src="{{ asset('assets/images/app_store.png') }}" alt="Playstore"
                                                    style="width: 44% !important;border: none;height: auto;border-radius: 8px !important;"></a>
                                            <a target="_blank"
                                                href="https://play.google.com/store/search?q=picastro&c=apps&hl=en"><img
                                                    src="{{ asset('assets/images/Google-Play-Store-Logo-PNG-Transparent.png') }}"
                                                    alt="Playstore"
                                                    style="width: 49% !important;border-radius: 10px;border: none;height: auto;"></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        </div>
        <!-- end main content-->

    </div>

    @include('includes.script')
</body>

</html>
