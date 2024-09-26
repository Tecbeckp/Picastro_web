<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default" >


<head>

    <meta charset="utf-8" />
    <title>Users | Picastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @include('includes.style') 
    <link href="{{asset('assets/app.min.css')}}" rel="stylesheet" type="text/css" />

</head>

<body>

    
        <div class="main-content-app">

            <div class="page-content">
                <div class="container-fluid">

					<!-- App Listing -->
					<div class="row">
                        @forelse ($posts as $item)
						<div class="col-6 col-sm-6">
                            <div class="card small-card mb-0">
								<div class="card-body p-0">
									<span class="d-flex align-items-center isProfile">
										<img class="rounded-circle header-profile-user" src="{{$item['user']['profile_image']}}" alt="Header Avatar">
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