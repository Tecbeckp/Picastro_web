@extends('layouts.app')
@section('title','Posts Detail | Picastro')
@section('content')

<div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mt-n4 mx-n4">
                                <div class="bg-light-subtle">
                                    <div class="card-body pb-0 px-4">
                                        <div class="row mb-3">
                                            <div class="col-md">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-md-auto">
                                                        <div class="avatar-md">
                                                            <div class="avatar avatar-title bg-white rounded-circle">
                                                                <img src="{{asset('assets/images/brands/slack.png')}}" alt="" class="avatar-xs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md">
                                                        <div>
                                                            <h4 class="fw-bold">{{$post->catalogue_number ?? $post->post_image_title}}</h4>
                                                            <div class="hstack gap-3 flex-wrap">
                                                                <div><i class="ri-user-line align-bottom me-1"></i> Created By : <span class="fw-medium">{{$post->user ? $post->user->username : N/A }}</span></div>
                                                                <div class="vr"></div>
                                                                <div><i class="ri-calendar-line align-bottom me-1"></i> Created Date : <span class="fw-medium">{{ date('d M,Y',strtotime($post->created_at))}}</span></div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-overview" role="tab">
                                                    Overview
                                                </a>
                                            </li>
                                           
                                            <li class="nav-item">
                                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                                    Activities
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-team" role="tab">
                                                    StarCard
                                                </a>
                                            </li>
											<li class="nav-item">
                                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-reports" role="tab">
                                                    Reports
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- end card body -->
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content text-muted">
                                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xl-9 col-lg-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="text-muted">
                                                        <h6 class="mb-3 fw-semibold text-uppercase">Summary</h6>
                                                        <p>{{$post->description}}</p>

                                                        

                                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                                            <div class="row gy-3">

                                                                <div class="col-lg-3 col-sm-6">
                                                                    <div>
                                                                        <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                                                        <div class="badge bg-success-subtle text-success fs-12">Complete</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->

                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1">Comments</h4>
                                                    <div class="flex-shrink-0">
                                                        <div class="dropdown card-header-dropdown">
                                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="text-muted">Recent<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#">Recent</a>
                                                                <a class="dropdown-item" href="#">Top Rated</a>
                                                                <a class="dropdown-item" href="#">Previous</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card header -->

                                                <div class="card-body">

                                                    <div data-simplebar style="height: 300px;" class="px-3 mx-n3 mb-2">
                                                        @forelse ($post_comments as $comment)
                                                        <div class="d-flex mb-4">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{$comment->user->userprofile->profile_image}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h5 class="fs-13">{{$comment->user->first_name.' '.$comment->user->last_name}}<small class="text-muted ms-2">{{date('d M Y - H:i A',strtotime($comment->created_at))}}</small></h5>
                                                                <p class="text-muted">{{$comment->comment}}</p>
                                                                @if($comment->ReplyComment->isNotEmpty())
                                                                    @forelse ($comment->ReplyComment as $reply)
                                                                    <a href="javascript: void(0);" class="badge text-muted bg-light"><i class="mdi mdi-reply"></i> Reply</a>
                                                                    <div class="d-flex mt-4">
                                                                        <div class="flex-shrink-0">
                                                                            <img src="{{$reply->user->userprofile->profile_image}}" alt="" class="avatar-xs rounded-circle" />
                                                                        </div>
                                                                        <div class="flex-grow-1 ms-3">
                                                                            <h5 class="fs-13">{{$reply->user->first_name.' '.$reply->user->last_name}} <small class="text-muted ms-2">{{date('d M Y - H:i A',strtotime($reply->created_at))}}</small></h5>
                                                                            <p class="text-muted">{{$reply->comment}}</p>
                                                                            <a href="javascript: void(0);" class="badge text-muted bg-light"><i class="mdi mdi-reply"></i> Reply</a>
                                                                        </div>
                                                                    </div>
                                                                    @empty
                                                                        
                                                                    @endforelse
                                                              
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @empty
                                                            <h4>Not Found</h4>
                                                        @endforelse
                                                        
                                                        
                                                    </div>
                                                    
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!-- ene col -->
                                        <div class="col-xl-3 col-lg-4">
                                            
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                                    <h4 class="card-title mb-0 flex-grow-1">Followers</h4>
                                                </div>

                                                <div class="card-body">
                                                    <div data-simplebar style="height: 235px;" class="mx-n3 px-3">
                                                        <div class="vstack gap-3">
                                                            @if($post->Follower->isNotEmpty())
                                                            @forelse ($post->Follower as $user)
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-xs flex-shrink-0 me-3">
                                                                    <img src="{{$user->follower->userprofile->profile_image}}" alt="" class="img-fluid rounded-circle">
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">{{$user->follower->first_name.' '.$user->follower->last_name}}</a></h5>
                                                                </div>
                                                                
                                                            </div>
                                                            <!-- end member item -->
                                                            @empty
                                                                
                                                            @endforelse
                                                            @endif
                                                            
                                                        </div>
                                                        <!-- end list -->
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->

                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->
                                </div>
                               
                                <div class="tab-pane fade" id="project-activities" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Activities</h5>
                                            <div class="acitivity-timeline py-3">
                                                <div class="acitivity-item d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Oliver Phillips <span class="badge bg-primary-subtle text-primary align-middle">New</span></h6>
                                                        <p class="text-muted mb-2">We talked about a project on linkedin.</p>
                                                        <small class="mb-0 text-muted">Today</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                                            N
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Nancy Martino <span class="badge bg-secondary-subtle text-secondary align-middle">In Progress</span></h6>
                                                        <p class="text-muted mb-2"><i class="ri-file-text-line align-middle ms-2"></i> Create new project Building product</p>
                                                        <div class="avatar-group mb-2">
                                                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                                <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle avatar-xs" />
                                                            </a>
                                                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                                <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle avatar-xs" />
                                                            </a>
                                                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title rounded-circle bg-light text-primary">
                                                                        R
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="more">
                                                                <div class="avatar-xs">
                                                                    <div class="avatar-title rounded-circle">
                                                                        2+
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <small class="mb-0 text-muted">Yesterday</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Natasha Carey <span class="badge bg-success-subtle text-success align-middle">Completed</span></h6>
                                                        <p class="text-muted mb-2">Adding a new event with attachments</p>
                                                        <div class="row">
                                                            <div class="col-xxl-4">
                                                                <div class="row border border-dashed gx-2 p-2 mb-2">
                                                                    <div class="col-4">
                                                                        <img src="assets/images/small/img-2.jpg" alt="" class="img-fluid rounded" />
                                                                    </div>
                                                                    <!--end col-->
                                                                    <div class="col-4">
                                                                        <img src="assets/images/small/img-3.jpg" alt="" class="img-fluid rounded" />
                                                                    </div>
                                                                    <!--end col-->
                                                                    <div class="col-4">
                                                                        <img src="assets/images/small/img-4.jpg" alt="" class="img-fluid rounded" />
                                                                    </div>
                                                                    <!--end col-->
                                                                </div>
                                                                <!--end row-->
                                                            </div>
                                                        </div>
                                                        <small class="mb-0 text-muted">25 Nov</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-6.jpg" alt="" class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Bethany Johnson</h6>
                                                        <p class="text-muted mb-2">added a new member to velzon dashboard</p>
                                                        <small class="mb-0 text-muted">19 Nov</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                                                <i class="ri-shopping-bag-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Your order is placed <span class="badge bg-danger-subtle text-danger align-middle ms-1">Out of Delivery</span></h6>
                                                        <p class="text-muted mb-2">These customers can rest assured their order has been placed.</p>
                                                        <small class="mb-0 text-muted">16 Nov</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-7.jpg" alt="" class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Lewis Pratt</h6>
                                                        <p class="text-muted mb-2">They all have something to say beyond the words on the page. They can come across as casual or neutral, exotic or graphic. </p>
                                                        <small class="mb-0 text-muted">22 Oct</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div class="avatar-title rounded-circle bg-info-subtle text-info">
                                                                <i class="ri-line-chart-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Monthly sales report</h6>
                                                        <p class="text-muted mb-2"><span class="text-danger">2 days left</span> notification to submit the monthly sales report. <a href="javascript:void(0);" class="link-warning text-decoration-underline">Reports Builder</a></p>
                                                        <small class="mb-0 text-muted">15 Oct</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-8.jpg" alt="" class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">New ticket received <span class="badge bg-success-subtle text-success align-middle">Completed</span></h6>
                                                        <p class="text-muted mb-2">User <span class="text-secondary">Erica245</span> submitted a ticket.</p>
                                                        <small class="mb-0 text-muted">26 Aug</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end card-body-->
                                    </div>
                                    <!--end card-->
                                </div>
                                <!-- end tab pane -->
                                <div class="tab-pane fade" id="project-team" role="tabpanel">
                                    <div class="card">
                                		<div class="card-body">
                                  			<p class="text-white mb-2">Step 1 of 1</p>
                                    		<div class="row align-items-center">
                                                <div class="col-sm-6">
                                                    <span class="bg-primary-subtle text-white rounded p-2 d-block">
                                                        OSC Camera
                                                    </span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        Your Setup
                                                    </span>
                                                </div>
                                            </div>
											
											<p class="text-white mb-2 mt-4">OSC</p>
                                    		<div class="row align-items-center">
                                                <div class="col-sm-4">
                                                    <span class="bg-primary-subtle text-white rounded p-2 d-block">
                                                        Tot. No. of light frames : 4
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        Light frame exposure : 300
                                                    </span>
                                                </div>
												<div class="col-sm-4">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        Cooling
                                                    </span>
                                                </div>
                                            </div>
											
											<p class="text-white mb-2 mt-4">Calibration (Total number)</p>
                                    		<div class="row align-items-center">
                                                <div class="col-sm-6">
                                                    <span class="bg-primary-subtle text-white rounded p-2 d-block">
                                                        No.  of darks : 4
                                                    </span>
                                                </div>
                                                <div class="col-sm-6 mt-2">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        No.  of flats : 5
                                                    </span>
                                                </div>
												<div class="col-sm-6">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        No.  of darks flats : 6
                                                    </span>
                                                </div>
												<div class="col-sm-6 mt-2">
                                                    <span class="bg-primary-subtle text-white rounded p-2 mt-2 mt-sm-0 d-block">
                                                        No.   of bias : 7
                                                    </span>
                                                </div>
                                            </div>
												
                                		</div>
                                		<!-- end card body -->
									</div>
                                    
                                </div>
                                <!-- end tab pane -->
								
								<div class="tab-pane fade" id="project-reports" role="tabpanel">
                                    <div class="card">
                                		<div class="card-body">
                                  
                                    		<div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <h5 class="card-title flex-grow-1">Post Reports</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive table-card">
                                                        <table class="table table-borderless align-middle mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col">Report</th>
                                                                    <th scope="col">Reported By</th>
                                                                    <th scope="col">Reported Date</th>
                                                                    <th scope="col" style="width: 120px;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($reports as $report)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            
                                                                            <div class="flex-grow-1">
                                                                                {{$report->reason}}
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{$report->user->username}}</td>
                                                                    <td>{{date('d M Y',strtotime($report->created_at))}}</td>
                                                                    <td>
                                                                        <ul class="list-inline hstack gap-2 mb-0">
																			<li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
																			<a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteCamp">
																				<i class="ri-delete-bin-5-fill fs-16"></i>
																			</a>
																		</li>
																		</ul>
                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td class="text-center" colspan="4">No Record Found</td>
                                                                    </tr>
                                                                @endforelse
                                                               
                                                              </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                		</div>
                                		<!-- end card body -->
									</div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
            </div>
@endsection

@push('script')
<script>
    $(document).ready(function(){
    });
</script>
@endpush