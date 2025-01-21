@extends('layouts.app')
@section('title', 'Rating | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Rating</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">Rating</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0">Content</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <form method="POST" action="{{ route('updateRatingPopup') }}">
                                @csrf
                                <div class="card-text">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="10" required>{{ @$data->description }}</textarea>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary mt-3" type="submit">Update</button>
                            </form>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0">User Rating List</h4>
                        </div><!-- end card header -->

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
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let table = '';
            table = $('#userTable').DataTable({
                "ordering": false,
                "searching": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "{{ route('rating-popup') }}"
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
                    }
                ]
            });
        });
    </script>
@endpush
