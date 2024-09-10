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
                                                <div class="avatar-title bg-white rounded-circle">
                                                    <img src="{{asset('assets/images/brands/slack.png')}}" alt="" class="avatar-xs">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div>
                                                <h4 class="fw-bold">{{$starcamp->name}}</h4>
                                                <div class="hstack gap-3 flex-wrap">
                                                    <div><i class="ri-user-line align-bottom me-1"></i> Created By : <span class="fw-medium"> {{$starcamp->user->username}}</span></div>
                                                    <div class="vr"></div>
                                                    <div><i class="ri-calendar-line align-bottom me-1"></i> Created Date : <span class="fw-medium">{{date('d M,Y',strtotime($starcamp->created_at))}}</span></div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="hstack gap-1 flex-wrap">
                                        <button type="button" class="btn py-0 fs-16 favourite-btn material-shadow-none active">
                                            <i class="ri-star-fill"></i>
                                        </button>
                                        
                                    </div>
                                </div>
                            </div>

                            <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                                
                                
                                <li class="nav-item">
                                    <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-team" role="tab">
                                        Members
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                        Activities
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
                   
                     <div class="tab-pane show active fade" id="project-team" role="tabpanel">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex">
                                    <div class="search-box me-2">
                                        <input type="text" class="form-control" placeholder="Search member...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#inviteMembersModal"><i class="ri-share-line me-1 align-bottom"></i> Invite Member</button>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="team-list list-view-filter">
                            @forelse ($starcamp->starcampMember as $member)
                            <div class="card team-box">
                                <div class="card-body px-4">
                                    <div class="row align-items-center team-row">
                                        
                                        <div class="col-lg-4 col">
                                            <div class="team-profile-img">
                                                <div class="avatar avatar-lg img-thumbnail rounded-circle">
                                                    <img src="{{$member->user->userProfile->profile_image}}" alt="" class="img-fluid d-block rounded-circle" />
                                                </div>
                                                <div class="team-content">
                                                    <a href="#" class="d-block">
                                                        <h5 class="fs-16 mb-1">{{$member->user->first_name.' '. $member->user->last_name}}</h5>
                                                    </a>
                                                    <p class="text-muted mb-0">Member</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h5 class="mb-1">{{$member->Post->count()}}</h5>
                                                    <p class="text-muted mb-0">Posts</p>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="mb-1">{{$member->memberStarcamp->count()}}</h5>
                                                    <p class="text-muted mb-0">StarCamps</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col">
                                            <div class="text-end">
                                                <a href="{{route('users.show',encrypt($member->member_id))}}" class="btn btn-light view-btn">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                                
                            @endforelse
                          
                            <!--end card-->
                        </div>
                        <!-- end team list -->

                        <div class="row g-0 text-center text-sm-start align-items-center mb-3">
                            <div class="col-sm-6">
                                <div>
                                    <p class="mb-sm-0">Showing 1 to 10 of 12 entries</p>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-sm-6">
                                <ul class="pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    <li class="page-item disabled"> <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a> </li>
                                    <li class="page-item"> <a href="#" class="page-link">1</a> </li>
                                    <li class="page-item active"> <a href="#" class="page-link">2</a> </li>
                                    <li class="page-item"> <a href="#" class="page-link">3</a> </li>
                                    <li class="page-item"> <a href="#" class="page-link">4</a> </li>
                                    <li class="page-item"> <a href="#" class="page-link">5</a> </li>
                                    <li class="page-item"> <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a> </li>
                                </ul>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div>
                    <!-- end tab pane -->
                    <div class="tab-pane  fade" id="project-activities" role="tabpanel">
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