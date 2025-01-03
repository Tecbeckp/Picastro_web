@extends('layouts.app')
@section('title', 'Users | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Users</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="customerList">
                        <div class="card-header border-bottom-dashed">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">Users List</h5>
                                <div class="flex-shrink-0">
                                   <div class="d-flex flex-wrap gap-2">
                                        <a href="{{route('exportUser', ['subscription' => '1'])}}" class="btn btn-danger add-btn">Export Paid Users</a>
                                        <a href="{{route('exportUser', ['subscription' => '0'])}}" class="btn btn-danger add-btn">Export Unpaid users</a>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-bottom-dashed border-bottom">
                            <form>
                                <div class="row g-3">
                                    <div class="col-xl-4">
                                        <div class="search-box">
                                            <input type="text" class="form-control search"
                                                placeholder="Search for customer, email, username, name">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-xl-8">
                                        <div class="row g-3">
                                            <div class="col-sm-3">
                                                <div class="">
                                                    <input type="text" class="form-control date" id="datepicker-range"
                                                        data-provider="flatpickr" data-date-format="d M, Y"
                                                        data-range-date="true" placeholder="Select date">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-sm-3">
                                                <div>
                                                    <select class="form-control status" data-plugin="choices" data-choices
                                                        data-choices-search-false name="choices-single-default"
                                                        id="idStatus">
                                                        <option value="" selected disabled>Status</option>
                                                        <option value="">All</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Block</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-sm-4">
                                                <div>
                                                    <select class="form-control subscription" data-plugin="choices"
                                                        data-choices data-choices-search-false name="choices-single-default"
                                                        id="idSubscription">
                                                        <option value="" selected disabled>Subscription</option>
                                                        <option value="">All</option>
                                                        <option value="1">Paid</option>
                                                        <option value="0">Unpaid</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <div class="col-sm-2">
                                                <div>
                                                    <button type="button" class="btn btn-primary w-100" id="filter"> <i
                                                            class="ri-equalizer-fill me-2 align-bottom"></i>Filters</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="table-responsive">
                                    <table id="userTable" class="table nowrap align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>User</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>platform</th>
                                                <th>Subscription Type</th>
                                                <th>Subscription</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div>
        <!-- container-fluid -->
    </div>

@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            let table = '';
            table = $('#userTable').DataTable({
                "ordering": false,
                "searching": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "{{ route('getAllUser') }}",
                    data: function(d) {
                        d.search = $('.search').val(),
                            d.date = $('.date').val(),
                            d.status = $('.status').val(),
                            d.subscription = $('.subscription').val()
                    }
                },
                "columns": [{
                        data: 'ID',
                        name: 'ID'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'platform',
                        name: 'platform'
                    },
                    {
                        data: 'subscription_type',
                        name: 'subscription_type'
                    },
                    {
                        data: 'subscription',
                        name: 'subscription'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('#filter').on('click', function() {
                $('#userTable tbody').html('');
                table.draw();
            });
        });

        function deleteConfirmation(id) {
            swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover",
                icon: "warning",
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Delete',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                // width:300,
                allowOutsideClick: false
            }).then((willDelete) => {
                console.log(willDelete);
                if (willDelete.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('users') }}" + '/' + id,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.success == true) {
                                swal.fire("success", response.message, "success").then(function() {
                                    location.reload();
                                });
                            } else {
                                swal.fire("error", response.message, "error");
                            }

                        }
                    });
                }
            });
        }
    </script>
@endpush
