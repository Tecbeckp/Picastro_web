<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default" >


<head>

    <meta charset="utf-8" />
    <title>User Posts | Picastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @include('includes.style') 
    <link href="{{asset('assets/app.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
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
      </style>
</head>

<body>

    
        <div class="main-content-app">

            <div class="page-content">
                <div class="container-fluid">

					<!-- App Listing -->
					<div class="row">
                        <div class="col-12 col-sm-12" style="padding: 0">
                            <div class="profile-card small-card mb-0">
                                <div class="d-flex align-items-center">
                                  <img src="{{$user->userprofile->profile_image}}" alt="Profile Picture">
                                  <div class="ms-3">
                                    <h5 class="mb-0">{{$user->username}}</h5>
                                    <small class="text-muted">{{$user->userprofile->pronouns}}</small>
                                  </div>
                                  <span class="ms-auto text-warning">★ 1,945</span>
                                </div>
                            
                                <div class="stats mt-3">
                                  <div>
                                    <span class="fw-bold">{{$posts->count()}}</span>
                                    <div>Posts</div>
                                  </div>
                                  <div>
                                    <span class="fw-bold">{{$user->userprofile->followers}}</span>
                                    <div>Followers</div>
                                  </div>
                                  <div>
                                    <span class="fw-bold">{{$user->userprofile->following}}</span>
                                    <div>Following</div>
                                  </div>
                                </div>
                            
                                <p class="mt-3">{{$user->userprofile->bio}}</p>
                            
                                <div class="links mt-2 d-flex gap-3">
                                  <a href="https://{{$user->userprofile->web_site_one}}" target="_blank">{{$user->userprofile->web_site_one}}</a>
                                  <a href="https://{{$user->userprofile->web_site_two}}" target="_blank">{{$user->userprofile->web_site_two}}</a>
                                </div>
                            
                                <div class="d-flex justify-content-between">
                                    <div class="badges d-flex gap-2 mt-3">
                                        <img src="https://via.placeholder.com/25" alt="Badge 1">
                                        <img src="https://via.placeholder.com/25" alt="Badge 2">
                                        <img src="https://via.placeholder.com/25" alt="Badge 3">
                                      </div>
                                  
                                      <button class="btn btn-follow">Follow</button>
                                </div>
                              </div>    
                        </div>
                        @forelse ($posts as $item)
                        
						<div class="col-6 col-sm-6">
                            <div class="card small-card mb-0">
								<div class="card-body p-0">
									<span class="d-flex align-items-center isProfile">
										<img class="rounded-circle header-profile-user" src="{{$item['user']['profile_image']}}" alt="{{$item['post_image_title']}}">
										<span class="text-start ms-xl-2">
											<span class=" d-xl-inline-block ms-1 fw-medium user-name-text">Picastro</span>
											
										</span>
									</span>
									<img src="{{$item['image']}}" alt="Space Image" class="spaceImg">
									<div class="card-footer">
                                        @if ($item['post_image_title'])
                                        <p class="mb-0">{{$item['post_image_title']}}
										</p>
                                        @else
                                        <p class="mb-0">{{$item['catalogue_number']}}
											<br><span class="fs-12">{{$item['object_name']}}</span>
										</p>
                                        @endif
										
										<i class="bx bx-star"></i>
									</div>
								</div>
							</div>
						</div>
                            @empty
                                
                            @endforelse
							
						
						
					</div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        </div>
        <!-- end main content-->

    

    @include('includes.script')
	
	
</body>

</html>