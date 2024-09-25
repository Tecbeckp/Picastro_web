@extends('layouts.app')
@section('title', 'Dashboard | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>

                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- start page title -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Allow Registrations</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <div class="form-check form-switch form-switch-right form-switch-md">
                                        <label for="ios" class="form-label text-muted">iOS</label>
                                        <input class="form-check-input" @if ($data['is_registration']->ios == '1') checked @endif
                                            type="checkbox" id="ios">
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-switch form-switch-right form-switch-md">
                                        <label for="android" class="form-label text-muted">Android</label>
                                        <input class="form-check-input" @if ($data['is_registration']->android == '1') checked @endif
                                            type="checkbox" id="android">
                                    </div>
                                </li>

                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row project-wrapper">
                <div class="col-xxl-8">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-2">
                                                <i data-feather="briefcase" class="text-primary"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden ms-3">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total Users
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['total_users'] }}">0</span></h4>
                                                <span class="badge bg-danger-subtle text-danger fs-12"><i
                                                        class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>5.02
                                                    %</span>
                                            </div>
                                            <p class="text-muted text-truncate mb-0">this month</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->

                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">Total Posts</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['total_post'] }}">0</span></h4>
                                                <span class="badge bg-success-subtle text-success fs-12"><i
                                                        class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58 %</span>
                                            </div>
                                            <p class="text-muted mb-0">this month</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->

                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle text-info rounded-2 fs-2">
                                                <i data-feather="clock" class="text-info"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden ms-3">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total
                                                StarCamps</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['total_starcamp'] }}">0</span></h4>
                                                <span class="badge bg-danger-subtle text-danger fs-12"><i
                                                        class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>10.35
                                                    %</span>
                                            </div>
                                            <p class="text-muted text-truncate mb-0">this month</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->

                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success-subtle text-success rounded-2 fs-2">
                                                <i class="ri-exchange-dollar-line text-success"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden ms-3">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total Reports
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['total_report'] }}">0</span></h4>
                                                <span class="badge bg-danger-subtle text-danger fs-12"><i
                                                        class="ri-arrow-down-s-line fs-13 align-middle me-1"></i>5.02
                                                    %</span>
                                            </div>
                                            <p class="text-muted text-truncate mb-0">this month</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->


                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Payments Overview</h4>
                                    <div>
                                        <button type="button" class="btn btn-soft-secondary btn-sm material-shadow-none">
                                            ALL
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm material-shadow-none">
                                            1M
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm material-shadow-none">
                                            6M
                                        </button>
                                        <button type="button" class="btn btn-soft-primary btn-sm material-shadow-none">
                                            1Y
                                        </button>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-header p-0 border-0 bg-light-subtle">
                                    <div class="row g-0 text-center">
                                        <div class="col-6 col-sm-3">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span class="counter-value"
                                                        data-target="{{ $data['total_subscriptions'] }}">0</span></h5>
                                                <p class="text-muted mb-0">Total Subscriptions</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-sm-3">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span class="counter-value"
                                                        data-target="{{ $data['active_subscription'] }}">0</span></h5>
                                                <p class="text-muted mb-0">Ongoing Subscriptions</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-sm-3">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span class="counter-value"
                                                        data-target="{{ $data['pending_subscription'] }}">0</span></h5>
                                                <p class="text-muted mb-0">Pending Subscriptions</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-sm-3">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span class="counter-value"
                                                        data-target="{{ $data['cancel_subscription'] }}">0</span></h5>
                                                <p class="text-muted mb-0">Cancelled Subscriptions</p>
                                            </div>
                                        </div>
                                        <!--end col-->

                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body p-0 pb-2">
                                    <div>
                                        <div id="projects-overview-chart"
                                            data-colors='["--vz-primary", "--vz-warning", "--vz-success"]'
                                            data-colors-minimal='["--vz-primary", "--vz-primary-rgb, 0.1", "--vz-primary-rgb, 0.50"]'
                                            data-colors-interactive='["--vz-primary", "--vz-info", "--vz-warning"]'
                                            data-colors-creative='["--vz-secondary", "--vz-warning", "--vz-success"]'
                                            data-colors-corporate='["--vz-primary", "--vz-secondary", "--vz-danger"]'
                                            data-colors-galaxy='["--vz-primary", "--vz-primary-rgb, 0.1", "--vz-primary-rgb, 0.50"]'
                                            data-colors-classic='["--vz-primary", "--vz-secondary", "--vz-warning"]'
                                            dir="ltr" class="apex-charts"></div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end col -->


            </div><!-- end row -->

            <div class="row">
                <div class="col-xxl-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Users</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                            class="text-muted">Last 30 Days<i
                                                class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Today</a>
                                        <a class="dropdown-item" href="#">Yesterday</a>
                                        <a class="dropdown-item" href="#">Last 7 Days</a>
                                        <a class="dropdown-item" href="#">Last 30 Days</a>
                                        <a class="dropdown-item" href="#">This Month</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">

                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-nowrap align-middle mb-0">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data['users'] as $user)
                                            <tr>
                                                <td class="d-flex">
                                                    <img src="{{ $user->userProfile->profile_image }}" alt=""
                                                        class="avatar-xs rounded-3 me-2 material-shadow">
                                                    <div>
                                                        <h5 class="fs-13 mb-0">{{ $user->first_name }}
                                                            {{ $user->last_name }}</h5>
                                                        <p class="fs-12 mb-0 text-muted">User</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">{{ $user->email }}</h6>
                                                </td>
                                                <td>
                                                    {{ $user->username ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($user->status == '1')
                                                        <span
                                                            class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                                    @else
                                                        <span
                                                            class="badge bg-warning-subtle text-warning text-uppercase">Blocked</span>
                                                    @endif
                                                </td>
                                            </tr><!-- end tr -->
                                        @empty
                                        @endforelse

                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col -->

                {{-- <div class="col-xxl-6 col-lg-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Trancations Overview</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="dropdown-btn text-muted" href="#" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        All Time <i class="mdi mdi-chevron-down ms-1"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">All Time</a>
                                        <a class="dropdown-item" href="#">Last 7 Days</a>
                                        <a class="dropdown-item" href="#">Last 30 Days</a>
                                        <a class="dropdown-item" href="#">Last 90 Days</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="prjects-status"
                                data-colors='["--vz-success", "--vz-primary", "--vz-warning", "--vz-danger"]'
                                data-colors-minimal='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.70", "--vz-primary-rgb, 0.50"]'
                                data-colors-galaxy='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.70", "--vz-primary-rgb, 0.50"]'
                                class="apex-charts" dir="ltr"></div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-center align-items-center mb-4">
                                    <h2 class="me-3 ff-secondary mb-0">0</h2>
                                    <div>
                                        <p class="text-muted mb-0">Transactions Invoices</p>
                                        <p class="text-success fw-medium mb-0">
                                            <span class="badge bg-success-subtle text-success p-1 rounded-circle"><i
                                                    class="ri-arrow-right-up-line"></i></span> +3 New
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                                    <p class="fw-medium mb-0"><i
                                            class="ri-checkbox-blank-circle-fill text-success align-middle me-2"></i>
                                        Transactions Sent</p>
                                    <div>
                                        <span class="text-muted pe-5">0</span>

                                    </div>
                                </div><!-- end -->
                                <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                                    <p class="fw-medium mb-0"><i
                                            class="ri-checkbox-blank-circle-fill text-primary align-middle me-2"></i> Paid
                                        Transactions</p>
                                    <div>
                                        <span class="text-muted pe-5">0</span>

                                    </div>
                                </div><!-- end -->
                                <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                                    <p class="fw-medium mb-0"><i
                                            class="ri-checkbox-blank-circle-fill text-warning align-middle me-2"></i>
                                        Unpaid Transactions</p>
                                    <div>
                                        <span class="text-muted pe-5">0</span>

                                    </div>
                                </div><!-- end -->
                                <div class="d-flex justify-content-between py-2">
                                    <p class="fw-medium mb-0"><i
                                            class="ri-checkbox-blank-circle-fill text-danger align-middle me-2"></i>
                                        Cancelled Transactions</p>
                                    <div>
                                        <span class="text-muted pe-5">0</span>

                                    </div>
                                </div><!-- end -->
                            </div>
                        </div><!-- end cardbody -->
                    </div><!-- end card -->
                </div><!-- end col --> --}}
            </div><!-- end row -->

        </div>
        <!-- container-fluid -->
    </div>
@endsection

@push('script')
    <script>
        $('#ios').change(function() {

            if ($(this).is(':checked')) {
                var status = true;
            } else {
                var status = false;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('allowRegistration') }}",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status,
                    platform_type: 'ios'
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.success === true) {
                        if (res.data == 1) {
                            var message = 'Enabled Successfuly';
                        } else {
                            var message = 'Disabled Successfuly';
                        }
                        Toast.fire({
                            icon: 'success',
                            title: message,
                        })
                    }
                },
                error: function(e) {}
            });
        })

        $('#android').change(function() {

            if ($(this).is(':checked')) {
                var status = true;
            } else {
                var status = false;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('allowRegistration') }}",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status,
                    platform_type: 'android'
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.success === true) {
                        if (res.data == 1) {
                            var message = 'Enabled Successfuly';
                        } else {
                            var message = 'Disabled Successfuly';
                        }
                        Toast.fire({
                            icon: 'success',
                            title: message,
                        })
                    }
                },
                error: function(e) {}
            });
        })
    </script>
@endpush
