@extends('layouts.app')
@section('title', 'Maintenance | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Maintenance</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">Maintenance</li>
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
                            <form method="POST" action="{{ route('updateMaintenance') }}">
                                @csrf
                                <div class="card-text">
                                    <input type="hidden" name="id" value="{{ @$data->id }}">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ @$data->maintenance_title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="10" required>{{ @$data->maintenance_description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">iOS version</label>
                                        <input type="text" name="ios_version"
                                            class="form-control" placeholder="e.g. 1.0.0" title="Please enter a valid version like 1.0.0" value="{{@$data->maintenance_ios_version}}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Android version</label>
                                        <input type="text" name="android_version"
                                            class="form-control" placeholder="e.g. 1.0.0" title="Please enter a valid version like 1.0.0" value="{{@$data->maintenance_android_version}}" required />
                                    </div>
                                    <div class="col-lg-6 mt-5">
                                        <h4 class="card-title mb-2">Maintenance</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="maintenance_ios" class="form-label text-muted">iOS</label>
                                                    <input class="form-check-input"
                                                        @if ($data->maintenance_ios == '1') checked @endif type="checkbox"
                                                        id="maintenance_ios" name="maintenance_ios">
                                                </div>
                                            </li>

                                            <li class="breadcrumb-item">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="maintenance_andriod"
                                                        class="form-label text-muted">Android</label>
                                                    <input class="form-check-input"
                                                        @if ($data->maintenance_android == '1') checked @endif type="checkbox"
                                                        id="maintenance_andriod" name="maintenance_andriod">
                                                </div>
                                            </li>

                                        </ol>
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

        </div> <!-- container-fluid -->
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
    <script type="text/javascript"></script>
@endpush
