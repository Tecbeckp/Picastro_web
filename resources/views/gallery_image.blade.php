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
        
        .main-content-app{
            max-width: 100%;
        }
    </style>
</head>

<body>

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
                            </div>

                        </div>
                    </div>
                    @forelse ($posts as $item)
                        <div class="col-6 col-sm-6" style="padding-bottom: 7px !important;">
                            <div class="card small-card mb-0">
                                <div class="card-body p-0">
                                    <img src="{{ $item['original_image'] }}" alt="Space Image" class="spaceImg" style="height: auto !important;">
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
                                    </div>
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
<div class="modal fade" id="autoLoadModal" tabindex="-1" aria-labelledby="autoLoadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="autoLoadForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="autoLoadModalLabel">Enter Password</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="hidden" value="{{$user->userprofile->gallery_password}}" id="current_password" />
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
    @include('includes.script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var autoLoadModal = new bootstrap.Modal(document.getElementById('autoLoadModal'), {
            backdrop: 'static', // Prevent clicking outside the modal to close it
            keyboard: false     // Disable closing with the Esc key
        });
        autoLoadModal.show();
        document.getElementById('autoLoadForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const passwordInput = document.getElementById('password').value;
            const currentPassword = document.getElementById('current_password').value;
            
            // Replace this with actual password validation logic
            if (passwordInput == currentPassword) {
                autoLoadModal.hide(); // Close the modal
                document.body.classList.remove('modal-open'); // Removes blur
                document.querySelector('.main-content-app').style.filter = 'none';
            } else {
                alert("Invalid password. Please try again.");
            }
        });
    });
    
    
</script>


</body>

</html>