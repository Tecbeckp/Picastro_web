@extends('layouts.app')
@section('title','User Detail | Picastro')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="{{asset('assets/images/profile-bg.jpg')}}" alt="" class="profile-wid-img" />
            </div>
        </div>
        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar avatar-lg">
                        <img src="{{$data['user']->userprofile ? $data['user']->userprofile->profile_image : null}}" alt="user-img" class="img-thumbnail rounded-circle" />
                    </div>
                </div>
                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">{{$data['user']->first_name}} {{$data['user']->last_name}}</h3>
                        <p class="text-white text-opacity-75">User</p>
                        
                    </div>
                </div>
                <!--end col-->
                <div class="col-12 col-lg-auto order-last order-lg-0">
                    <div class="row text text-white-50 text-center">
                        <div class="col-lg-12 col-12">
                            <div class="p-2">
                                <h4 class="text-white mb-1">{{$data['total_posts']}}</h4>
                                <p class="fs-14 mb-0">Posts</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->

            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                    <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Activities</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Posts</span>
                                </a>
                            </li>
                        </ul>
                        
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Info</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Full Name :</th>
                                                            <td class="text-muted">{{$data['user']->first_name}} {{$data['user']->last_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Username :</th>
                                                            <td class="text-muted">{{$data['user']->username}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">E-mail :</th>
                                                            <td class="text-muted">{{$data['user']->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Gender :</th>
                                                            <td class="text-muted">{{$data['user']->userprofile->pronouns ?? 'N/A'}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Joining Date</th>
                                                            <td class="text-muted">{{date('d M Y',strtotime($data['user']->created_at))}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->
                                <div class="col-xxl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Bio</h5>
                                            <p class="w-100 text-center">{{$data['user']->userprofile->bio ?? 'No Bio Found' }}</p>
                                            
                                            
                                        </div>
                                        <!--end card-body-->
                                    </div><!-- end card -->
                                    
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0  me-2">Recent Activity</h4>
                                                    <div class="flex-shrink-0 ms-auto">
                                                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#today" role="tab">
                                                                    Today
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#weekly" role="tab">
                                                                    Weekly
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#monthly" role="tab">
                                                                    Monthly
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content text-muted">
                                                        <div class="tab-pane active" id="today" role="tabpanel">
                                                            <div class="profile-timeline">
                                                                <div class="accordion accordion-flush" id="todayExample">
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="headingOne">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Jacqueline Steve
                                                                                        </h6>
                                                                                        <small class="text-muted">We has changed 2 attributes on 05:16PM</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                In an awareness campaign, it is vital for people to begin put 2 and 2 together and begin to recognize your cause. Too much or too little spacing, as in the example below, can make things unpleasant for the reader. The goal is to make your text as comfortable to read as possible. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="headingTwo">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-success rounded-circle material-shadow">
                                                                                            M
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Megan Elmore
                                                                                        </h6>
                                                                                        <small class="text-muted">Adding a new event with attachments - 04:45PM</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                <div class="row g-2">
                                                                                    <div class="col-auto">
                                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                            <div class="flex-shrink-0">
                                                                                                <i class="ri-image-2-line fs-17 text-danger"></i>
                                                                                            </div>
                                                                                            <div class="flex-grow-1 ms-2">
                                                                                                <h6>
                                                                                                    <a href="javascript:void(0);" class="stretched-link">Business Template - UI/UX design</a>
                                                                                                </h6>
                                                                                                <small>685 KB</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-auto">
                                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                            <div class="flex-shrink-0">
                                                                                                <i class="ri-file-zip-line fs-17 text-info"></i>
                                                                                            </div>
                                                                                            <div class="flex-grow-1 ms-2">
                                                                                                <h6 class="mb-0">
                                                                                                    <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                                                                </h6>
                                                                                                <small>8.78 MB</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="headingThree">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapsethree" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-5.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1"> New ticket received</h6>
                                                                                        <small class="text-muted mb-2">User <span class="text-secondary">Erica245</span> submitted a ticket - 02:33PM</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="headingFour">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFour" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-muted rounded-circle material-shadow">
                                                                                            <i class="ri-user-3-fill"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Nancy Martino
                                                                                        </h6>
                                                                                        <small class="text-muted">Commented on 12:57PM</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                                " A wonderful serenity has
                                                                                taken possession of my
                                                                                entire soul, like these
                                                                                sweet mornings of spring
                                                                                which I enjoy with my whole
                                                                                heart. Each design is a new,
                                                                                unique piece of art birthed
                                                                                into this world, and while
                                                                                you have the opportunity to
                                                                                be creative and make your
                                                                                own style choices. "
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="headingFive">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFive" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-7.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Lewis Arnold
                                                                                        </h6>
                                                                                        <small class="text-muted">Create new project buildng product - 10:05AM</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                <p class="text-muted mb-2"> Every team project can have a velzon. Use the velzon to share information with your team to understand and contribute to your project.</p>
                                                                                <div class="avatar-group">
                                                                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                                                        <img src="{{asset('assets/images/users/avatar-4.jpg')}}" alt="" class="rounded-circle avatar-xs material-shadow">
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                                                        <img src="{{asset('assets/images/users/avatar-3.jpg')}}" alt="" class="rounded-circle avatar-xs material-shadow">
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                                                        <div class="avatar-xs">
                                                                                            <div class="avatar-title rounded-circle bg-light text-primary material-shadow">
                                                                                                R
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="more">
                                                                                        <div class="avatar-xs">
                                                                                            <div class="avatar-title rounded-circle material-shadow">
                                                                                                2+
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--end accordion-->
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="weekly" role="tabpanel">
                                                            <div class="profile-timeline">
                                                                <div class="accordion accordion-flush" id="weeklyExample">
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading6">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse6" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-3.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Joseph Parker
                                                                                        </h6>
                                                                                        <small class="text-muted">New people joined with our company - Yesterday</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse6" class="accordion-collapse collapse show" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                It makes a statement, it’s
                                                                                impressive graphic design.
                                                                                Increase or decrease the
                                                                                letter spacing depending on
                                                                                the situation and try, try
                                                                                again until it looks right,
                                                                                and each letter has the
                                                                                perfect spot of its own.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading7">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse7" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="avatar-xs">
                                                                                        <div class="avatar-title rounded-circle bg-light text-danger material-shadow">
                                                                                            <i class="ri-shopping-bag-line"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Your order is placed <span class="badge bg-success-subtle text-success align-middle">Completed</span>
                                                                                        </h6>
                                                                                        <small class="text-muted">These customers can rest assured their order has been placed - 1 week Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading8">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse8" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-success rounded-circle material-shadow">
                                                                                            <i class="ri-home-3-line"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Velzon admin dashboard templates layout upload
                                                                                        </h6>
                                                                                        <small class="text-muted">We talked about a project on linkedin - 1 week Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse8" class="accordion-collapse collapse show" aria-labelledby="heading8" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                                Powerful, clean & modern
                                                                                responsive bootstrap 5 admin
                                                                                template. The maximum file
                                                                                size for uploads in this demo :
                                                                                <div class="row mt-2">
                                                                                    <div class="col-xxl-6">
                                                                                        <div class="row border border-dashed gx-2 p-2">
                                                                                            <div class="col-3">
                                                                                                <img src="{{asset('assets/images/small/img-3.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                                                            </div>
                                                                                            <!--end col-->
                                                                                            <div class="col-3">
                                                                                                <img src="{{asset('assets/images/small/img-5.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                                                            </div>
                                                                                            <!--end col-->
                                                                                            <div class="col-3">
                                                                                                <img src="{{asset('assets/images/small/img-7.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                                                            </div>
                                                                                            <!--end col-->
                                                                                            <div class="col-3">
                                                                                                <img src="{{asset('assets/images/small/img-9.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                                                            </div>
                                                                                            <!--end col-->
                                                                                        </div>
                                                                                        <!--end row-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading9">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse9" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-6.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            New ticket created <span class="badge bg-info-subtle text-info align-middle">Starcard</span>
                                                                                        </h6>
                                                                                        <small class="text-muted mb-2">User <span class="text-secondary">Jack365</span> submitted a ticket - 2 week Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading10">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse10" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-5.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Jennifer Carter
                                                                                        </h6>
                                                                                        <small class="text-muted">Commented - 4 week Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse10" class="accordion-collapse collapse show" aria-labelledby="heading10" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                <p class="text-muted fst-italic mb-2">
                                                                                    " This is an awesome
                                                                                    admin dashboard
                                                                                    template. It is
                                                                                    extremely well
                                                                                    structured and uses
                                                                                    state of the art
                                                                                    components (e.g. one of
                                                                                    the only templates using
                                                                                    boostrap 5.1.3 so far).
                                                                                    I integrated it into a
                                                                                    Rails 6 project. Needs
                                                                                    manual integration work
                                                                                    of course but the
                                                                                    template structure made
                                                                                    it easy. "</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--end accordion-->
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="monthly" role="tabpanel">
                                                            <div class="profile-timeline">
                                                                <div class="accordion accordion-flush" id="monthlyExample">
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading11">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse11" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-success rounded-circle material-shadow">
                                                                                            M
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Megan Elmore
                                                                                        </h6>
                                                                                        <small class="text-muted">Adding a new event with attachments - 1 month Ago.</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse11" class="accordion-collapse collapse show" aria-labelledby="heading11" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                <div class="row g-2">
                                                                                    <div class="col-auto">
                                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                            <div class="flex-shrink-0">
                                                                                                <i class="ri-image-2-line fs-17 text-danger"></i>
                                                                                            </div>
                                                                                            <div class="flex-grow-1 ms-2">
                                                                                                <h6 class="mb-0">
                                                                                                    <a href="javascript:void(0);" class="stretched-link">Business Template - UI/UX design</a>
                                                                                                </h6>
                                                                                                <small>685 KB</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-auto">
                                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                            <div class="flex-shrink-0">
                                                                                                <i class="ri-file-zip-line fs-17 text-info"></i>
                                                                                            </div>
                                                                                            <div class="flex-grow-1 ms-2">
                                                                                                <h6 class="mb-0">
                                                                                                    <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                                                                </h6>
                                                                                                <small>8.78 MB</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-auto">
                                                                                        <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                                                            <div class="flex-shrink-0">
                                                                                                <i class="ri-file-zip-line fs-17 text-info"></i>
                                                                                            </div>
                                                                                            <div class="flex-grow-1 ms-2">
                                                                                                <h6 class="mb-0">
                                                                                                    <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                                                                </h6>
                                                                                                <small>8.78 MB</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading12">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse12" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Jacqueline Steve
                                                                                        </h6>
                                                                                        <small class="text-muted">We has changed 2 attributes on 3 month Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse12" class="accordion-collapse collapse show" aria-labelledby="heading12" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                In an awareness campaign, it
                                                                                is vital for people to begin
                                                                                put 2 and 2 together and
                                                                                begin to recognize your
                                                                                cause. Too much or too
                                                                                little spacing, as in the
                                                                                example below, can make
                                                                                things unpleasant for the
                                                                                reader. The goal is to make
                                                                                your text as comfortable to
                                                                                read as possible. A
                                                                                wonderful serenity has taken
                                                                                possession of my entire
                                                                                soul, like these sweet
                                                                                mornings of spring which I
                                                                                enjoy with my whole heart.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading13">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse13" aria-expanded="false">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-5.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            New ticket received
                                                                                        </h6>
                                                                                        <small class="text-muted mb-2">User <span class="text-secondary">Erica245</span> submitted a ticket - 5 month Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading14">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse14" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0 avatar-xs">
                                                                                        <div class="avatar-title bg-light text-muted rounded-circle material-shadow">
                                                                                            <i class="ri-user-3-fill"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Nancy Martino
                                                                                        </h6>
                                                                                        <small class="text-muted">Commented on 24 Nov, 2021.</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse14" class="accordion-collapse collapse show" aria-labelledby="heading14" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5 fst-italic">
                                                                                " A wonderful serenity has
                                                                                taken possession of my
                                                                                entire soul, like these
                                                                                sweet mornings of spring
                                                                                which I enjoy with my whole
                                                                                heart. Each design is a new,
                                                                                unique piece of art birthed
                                                                                into this world, and while
                                                                                you have the opportunity to
                                                                                be creative and make your
                                                                                own style choices. "
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="accordion-item border-0">
                                                                        <div class="accordion-header" id="heading15">
                                                                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse15" aria-expanded="true">
                                                                                <div class="d-flex">
                                                                                    <div class="flex-shrink-0">
                                                                                        <img src="{{asset('assets/images/users/avatar-7.jpg')}}" alt="" class="avatar-xs rounded-circle material-shadow" />
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="fs-14 mb-1">
                                                                                            Lewis Arnold
                                                                                        </h6>
                                                                                        <small class="text-muted">Create new project buildng product - 8 month Ago</small>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div id="collapse15" class="accordion-collapse collapse show" aria-labelledby="heading15" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body ms-2 ps-5">
                                                                                <p class="text-muted mb-2">
                                                                                    Every team project can
                                                                                    have a velzon. Use the
                                                                                    velzon to share
                                                                                    information with your
                                                                                    team to understand and
                                                                                    contribute to your
                                                                                    project.</p>
                                                                                <div class="avatar-group">
                                                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                                                        <img src="{{asset('assets/images/users/avatar-4.jpg')}}" alt="" class="rounded-circle avatar-xs">
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                                                        <img src="{{asset('assets/images/users/avatar-3.jpg')}}" alt="" class="rounded-circle avatar-xs">
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                                                        <div class="avatar-xs">
                                                                                            <div class="avatar-title rounded-circle bg-light text-primary">
                                                                                                R
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="more">
                                                                                        <div class="avatar-xs">
                                                                                            <div class="avatar-title rounded-circle">
                                                                                                2+
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--end accordion-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->
                                    </div><!-- end row -->

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Posts</h5>
                                            <!-- Swiper -->
                                            <div class="swiper project-swiper mt-n4">
                                                <div class="d-flex justify-content-end gap-2 mb-2">
                                                    <div class="slider-button-prev">
                                                        <div class="avatar-title fs-18 rounded px-1 material-shadow">
                                                            <i class="ri-arrow-left-s-line"></i>
                                                        </div>
                                                    </div>
                                                    <div class="slider-button-next">
                                                        <div class="avatar-title fs-18 rounded px-1 material-shadow">
                                                            <i class="ri-arrow-right-s-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="swiper-wrapper" style="display: flex">
                                                    @php
                                                    $side_colors = ['secondary', 'success', 'danger', 'warning', 'info', 'dark'];
                                                @endphp
                                                    @forelse($data['posts'] as $index => $post)
                                                    @php
                                                    $sideColorClass = $side_colors[$index % count($side_colors)];
                                                    @endphp
                                                    <div class="swiper-slide">
                                                        <div class="card profile-project-card shadow-none profile-project-{{$sideColorClass}} mb-0 material-shadow">
                                                            <div class="card-body p-4">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1 text-muted overflow-hidden">
                                                                        <h5 class="fs-14 text-truncate mb-1">
                                                                            <a href="{{route('posts.show', encrypt($post->id))}}" class="text-body">ABC Project Customization</a>
                                                                        </h5>
                                                                        <p class="text-muted text-truncate mb-0"> Last Update : <span class="fw-semibold text-body">4 hr Ago</span></p>
                                                                    </div>
                                                                    <div class="flex-shrink-0 ms-2">
                                                                        <div class="badge bg-warning-subtle text-warning fs-10">Starcard</div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex mt-4">
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div>
                                                                                <h5 class="fs-12 text-muted mb-0"> Post Type :</h5>
                                                                            </div>
                                                                           
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- end card body -->
                                                        </div>
                                                        <!-- end card -->
                                                    </div>
                                                    @empty
                                                    <span class="w-100 text-center">No Posts Found</span>
                                                    @endforelse
                                                    <!-- end slide item -->
                                                </div>

                                            </div>

                                        </div>
                                        <!-- end card body -->
                                    </div><!-- end card -->

                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <div class="tab-pane fade" id="activities" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Activities</h5>
                                    <div class="acitivity-timeline">
                                        <div class="acitivity-item d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Oliver Phillips <span class="badge bg-primary-subtle text-primary align-middle">New</span></h6>
                                                <p class="text-muted mb-2">We talked about a project on linkedin.</p>
                                                <small class="mb-0 text-muted">Today</small>
                                            </div>
                                        </div>
                                        <div class="acitivity-item py-3 d-flex">
                                            <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                                <div class="avatar-title bg-success-subtle text-success rounded-circle material-shadow">
                                                    N
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Nancy Martino <span class="badge bg-secondary-subtle text-secondary align-middle">In Progress</span></h6>
                                                <p class="text-muted mb-2"><i class="ri-file-text-line align-middle ms-2"></i> Create new project Buildng product</p>
                                                <div class="avatar-group mb-2">
                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                        <img src="{{asset('assets/images/users/avatar-4.jpg')}}" alt="" class="rounded-circle avatar-xs" />
                                                    </a>
                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                        <img src="{{asset('assets/images/users/avatar-3.jpg')}}" alt="" class="rounded-circle avatar-xs" />
                                                    </a>
                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title rounded-circle bg-light text-primary material-shadow">
                                                                R
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="more">
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
                                                <img src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Natasha Carey 
                                                </h6>
                                                <p class="text-muted mb-2">Adding a new event with attachments</p>
                                                <div class="row">
                                                    <div class="col-xxl-4">
                                                        <div class="row border border-dashed gx-2 p-2 mb-2">
                                                            <div class="col-4">
                                                                <img src="{{asset('assets/images/small/img-2.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                            </div>
                                                            <!--end col-->
                                                            <div class="col-4">
                                                                <img src="{{asset('assets/images/small/img-3.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
                                                            </div>
                                                            <!--end col-->
                                                            <div class="col-4">
                                                                <img src="{{asset('assets/images/small/img-4.jpg')}}" alt="" class="img-fluid rounded material-shadow" />
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
                                                <img src="{{asset('assets/images/users/avatar-6.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
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
                                                    <div class="avatar-title rounded-circle bg-danger-subtle text-danger material-shadow">
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
                                                <img src="{{asset('assets/images/users/avatar-7.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Lewis Pratt</h6>
                                                <p class="text-muted mb-2">They all have something to say
                                                    beyond the words on the page. They can come across as
                                                    casual or neutral, exotic or graphic. </p>
                                                <small class="mb-0 text-muted">22 Oct</small>
                                            </div>
                                        </div>
                                        <div class="acitivity-item py-3 d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs acitivity-avatar">
                                                    <div class="avatar-title rounded-circle bg-info-subtle text-info material-shadow">
                                                        <i class="ri-line-chart-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Monthly sales report</h6>
                                                <p class="text-muted mb-2">
                                                      <span class="text-danger">2 days left</span> notification to submit the monthly sales report. <a href="javascript:void(0);" class="link-warning text-decoration-underline">Reports Builder</a>
                                                </p>
                                                <small class="mb-0 text-muted">15 Oct</small>
                                            </div>
                                        </div>
                                        <div class="acitivity-item d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{asset('assets/images/users/avatar-8.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">New ticket received </h6>
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
                        <!--end tab-pane-->
                        <div class="tab-pane fade" id="projects" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-warning material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Chat App Update</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">2 year Ago</span></p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="badge bg-warning-subtle text-warning fs-10">Starcard</div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-success material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">ABC Project Customization</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">2 month Ago</span></p>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-info material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Client - Frank Hook</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">1 hr Ago</span></p>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0"> Post Type :</h5>
                                                                </div>
                                                              
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-primary material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Velzon Project</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">11 hr Ago</span></p>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-danger material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Brand Logo Design</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">10 min Ago</span></p>
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-primary material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Chat App</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">8 hr Ago</span></p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="badge bg-warning-subtle text-warning fs-10">Starcard</div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-warning material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Project Update</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">48 min Ago</span></p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="badge bg-warning-subtle text-warning fs-10">Starcard</div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none profile-project-success material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Client - Jennifer</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">30 min Ago</span></p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="badge bg-primary-subtle text-primary fs-10">Process</div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0"> Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none mb-xxl-0 profile-project-info material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Bsuiness Template - UI/UX design</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">7 month Ago</span></p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end card body -->
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none mb-xxl-0  profile-project-success material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Update Project</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">1 month Ago</span></p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none mb-sm-0  profile-project-danger material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">Bank Management System</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">10 month Ago</span></p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="card profile-project-card shadow-none mb-0  profile-project-primary material-shadow">
                                                <div class="card-body p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1 text-muted overflow-hidden">
                                                            <h5 class="fs-14 text-truncate"><a href="post-detail.php" class="text-body">PSD to HTML Convert</a></h5>
                                                            <p class="text-muted text-truncate mb-0">Last Update : <span class="fw-semibold text-body">29 min Ago</span></p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="d-flex mt-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div>
                                                                    <h5 class="fs-12 text-muted mb-0">Post Type :</h5>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="mt-4">
                                                <ul class="pagination pagination-separated justify-content-center mb-0">
                                                    <li class="page-item disabled">
                                                        <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a href="javascript:void(0);" class="page-link">1</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="javascript:void(0);" class="page-link">2</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="javascript:void(0);" class="page-link">3</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="javascript:void(0);" class="page-link">4</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="javascript:void(0);" class="page-link">5</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="javascript:void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end tab-pane-->
                    </div>
                    <!--end tab-content-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div><!-- container-fluid -->
</div><!-- End Page-content -->
@endsection

@push('script')

<script>
$(document).ready(function() {
    let table = '';
    table = $('#userstarcamp').DataTable({
        "ordering": false,
        "searching": false,
        "processing": true,
        "serverSide": true,
        "ajax":{
            url: "{{ route('getUserStarCamp') }}",
            data: function (d) {
                d.search   = $('.search').val(),
                d.date     = $('.date').val(),
                d.status   = $('.status').val()
            }
        },
        "columns":[
            {data: 'ID', name: 'ID'},
            {data: 'user', name: 'user'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'gender', name: 'gender'},
            {data: 'date', name: 'date'},
            {data: 'status', name: 'status'},
            {data:'action',name:'action'}
        ]
     });
     $('#filter').on('click', function () {
        $('#userTable tbody').html('');
        table.draw();
    });
});
</script>
 <!-- swiper js -->
 <script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>

 <!-- profile init js -->
 <script src="{{asset('assets/js/pages/profile.init.js')}}"></script>
@endpush