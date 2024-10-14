@extends('layouts.app')
@section('title','Coupon List | Picastro')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Coupon List</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">StarCamps</a></li>
                            <li class="breadcrumb-item active">Coupon List</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="StarCoupon">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">All Coupon</h5>
                            <div class="flex-shrink-0">
                               <div class="d-flex flex-wrap gap-2">
                                    <a href="{{route('coupon.create')}}" class="btn btn-danger add-btn"><i class="ri-add-line align-bottom me-1"></i> Create Coupon</a>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form>
                            <div class="row g-3">
                                <div class="col-xxl-7 col-sm-6">
                                    <div class="search-box">
                                        <input type="text" class="form-control search bg-light border-light" placeholder="Search for StarCamps or something...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="col-xxl-3 col-sm-3">
                                   <input type="text" class="form-control date" id="datepicker-range" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" placeholder="Select date">
                                </div>
                                <!--end col-->

                                {{-- <div class="col-xxl-3 col-sm-3">
                                    <div class="input-light">
                                        <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                                            <option value="">Status</option>
                                            <option value="all" selected>All</option>
                                            <option value="New">New</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Inprogress">Inprogress</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <!--end col-->
                                <div class="col-xxl-2 col-sm-3">
                                    <button type="button" class="btn btn-primary w-100" id="filter"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                        Filters
                                    </button>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                    <!--end card-body-->
                    <div class="card-body">
                        <div class="mb-4">
                            <table id="couponTable" class="table nowrap align-middle" style="width:100%">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="sort" data-sort="id">ID</th>
                                        <th class="sort" data-sort="client_name">Coupon Code</th>
                                        <th class="sort" data-sort="assignedto">Coupon Type</th>
                                        <th class="sort" data-sort="assignedto">Discount</th>
                                        <th class="sort" data-sort="assignedto">status</th>
                                        <th class="sort" data-sort="assignedto">Expire Date</th>
                                        <th class="sort" data-sort="status">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!--end table-->
                            
                        </div>
                        
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
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
        table = $('#couponTable').DataTable({
            "ordering": false,
            "searching": false,
            "processing": true,
            "serverSide": true,
            "ajax":{
                url: "{{ route('coupon.index') }}",
                data: function (d) {
                    d.search   = $('.search').val(),
                    d.date     = $('.date').val()
                }
            },
            "columns":[
                {data: 'ID', name: 'ID'},
                {data: 'code', name: 'code'},
                {data: 'type', name: 'type'},
                {data: 'discount', name: 'discount'},
                {data: 'status', name: 'status'},
                {data: 'date', name: 'date'},
                {data:'action',name:'action'}
            ]
         });
         $('#filter').on('click', function () {
            $('#starcampTable tbody').html('');
            table.draw();
        });
    });
    </script>
@endpush