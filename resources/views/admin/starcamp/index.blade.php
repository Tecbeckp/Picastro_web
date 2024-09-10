@extends('layouts.app')
@section('title','StarCamps | Picastro')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">StarCamps List</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">StarCamps</a></li>
                            <li class="breadcrumb-item active">StarCamps List</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="StarCampsList">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">All StarCamps</h5>
                            <div class="flex-shrink-0">
                               <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Create StarCamp</button>
                                    <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
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
                            <table id="starcampTable" class="table nowrap align-middle" style="width:100%">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="sort" data-sort="id">ID</th>
                                        
                                        <th class="sort" data-sort="client_name">Title</th>
                                        <th class="sort" data-sort="assignedto">Members</th>
                                        <th class="sort" data-sort="assignedto">Created Date</th>
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

        

        <div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-info-subtle">
                        <h5 class="modal-title" id="exampleModalLabel">Create StarCamp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                    </div>
                    <form class="tablelist-form" autocomplete="off">
                        <div class="modal-body">
                            <input type="hidden" id="tasksId" />
                            <div class="row g-3">
                                
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div>
                                        <label for="tasksTitle-field" class="form-label">Title</label>
                                        <input type="text" id="tasksTitle-field" class="form-control" placeholder="Title" required />
                                    </div>
                                </div>
                                <!--end col-->
                               
                                <!--end col-->
                                <div class="col-lg-12">
                                    <label class="form-label">Assigned To</label>
                                    <div data-simplebar style="height: 95px;">
                                        <ul class="list-unstyled vstack gap-2 mb-0">
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-2.jpg" id="james-forbes">
                                                    <label class="form-check-label d-flex align-items-center" for="james-forbes">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">James Forbes</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-3.jpg" id="john-robles">
                                                    <label class="form-check-label d-flex align-items-center" for="john-robles">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-3.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">John Robles</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-4.jpg" id="mary-gant">
                                                    <label class="form-check-label d-flex align-items-center" for="mary-gant">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-4.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Mary Gant</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-1.jpg" id="curtis-saenz">
                                                    <label class="form-check-label d-flex align-items-center" for="curtis-saenz">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Curtis Saenz</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-5.jpg" id="virgie-price">
                                                    <label class="form-check-label d-flex align-items-center" for="virgie-price">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-5.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Virgie Price</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-10.jpg" id="anthony-mills">
                                                    <label class="form-check-label d-flex align-items-center" for="anthony-mills">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-10.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Anthony Mills</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-6.jpg" id="marian-angel">
                                                    <label class="form-check-label d-flex align-items-center" for="marian-angel">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-6.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Marian Angel</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-10.jpg" id="johnnie-walton">
                                                    <label class="form-check-label d-flex align-items-center" for="johnnie-walton">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-7.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Johnnie Walton</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-8.jpg" id="donna-weston">
                                                    <label class="form-check-label d-flex align-items-center" for="donna-weston">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-8.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Donna Weston</span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="assignedTo[]" value="avatar-9.jpg" id="diego-norris">
                                                    <label class="form-check-label d-flex align-items-center" for="diego-norris">
                                                        <span class="flex-shrink-0">
                                                            <img src="assets/images/users/avatar-9.jpg" alt="" class="avatar-xxs rounded-circle">
                                                        </span>
                                                        <span class="flex-grow-1 ms-2">Diego Norris</span>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!--end col-->
                                <div class="col-lg-6">
                                    <label for="ticket-status" class="form-label">Status</label>
                                    <select class="form-control" id="ticket-status">
                                        <option value="">Status</option>
                                        <option value="New">New</option>
                                        <option value="Inprogress">Inprogress</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                                <!--end col-->
                                
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="add-btn">Add StarCamp</button>
                                <!-- <button type="button" class="btn btn-success" id="edit-btn">Update Task</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end modal-->

    </div>
    <!-- container-fluid -->
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        let table = '';
        table = $('#starcampTable').DataTable({
            "ordering": false,
            "searching": false,
            "processing": true,
            "serverSide": true,
            "ajax":{
                url: "{{ route('getAllstarcamp') }}",
                data: function (d) {
                    d.search   = $('.search').val(),
                    d.date     = $('.date').val()
                }
            },
            "columns":[
                {data: 'ID', name: 'ID'},
                {data: 'name', name: 'name'},
                {data: 'member', name: 'member'},
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