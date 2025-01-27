@extends('layouts.app')
@section('title', 'Posts List | Picastro')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Posts List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Posts</a></li>
                                <li class="breadcrumb-item active">Post List</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row g-4 mb-3">
                <div class="col-sm-auto">

                </div>
                <div class="col-sm">
                    <div class="d-flex justify-content-sm-end gap-2">
                        <div class="search-box ms-2">
                            <input type="text" class="form-control" id="search-input" placeholder="Search...">
                            <i class="ri-search-line search-icon"></i>
                        </div>

                        <select class="form-control w-md" data-choices data-choices-search-false id="time">
                            <option value="All" selected>All</option>
                            <option value="Today">Today</option>
                            <option value="Yesterday">Yesterday</option>
                            <option value="Last 7 Days">Last 7 Days</option>
                            <option value="Last 30 Days">Last 30 Days</option>
                            <option value="This Month">This Month</option>
                            <option value="Last Year">Last Year</option>
                        </select>
                    </div>
                </div>
            </div>


            <div id="post-data">
                @include('admin.post.posts')
            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#time').on('change', function(event) {
                event.preventDefault();
                fetchProjects();
            });
            $('#search-input').on('keyup', function(event) {
                event.preventDefault();
                fetchProjects();
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchProjects(page);
            });

            function fetchProjects(page = 1) {
                let search = $('#search-input').val();
                let time = $('#time').val();
                $('#preloader').show();
                $.ajax({
                    url: "{{ route('posts.index') }}",
                    method: 'GET',
                    data: {
                        search: search,
                        time: time,
                        page: page
                    },
                    success: function(data) {
                        $('#post-data').html(data);
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
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
                        url: "{{ url('posts') }}" + '/' + id,
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
