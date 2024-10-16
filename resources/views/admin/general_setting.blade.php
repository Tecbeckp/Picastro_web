@extends('layouts.app')
@section('title', 'General Settings | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">General Settings</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">General Settings</li>
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
                            <h4 class="card-title mb-0">General Settings</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h4 class="card-title mb-4">Allow Registrations</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="ios" class="form-label text-muted">iOS</label>
                                                    <input class="form-check-input"
                                                        @if ($data['is_registration']->ios == '1') checked @endif type="checkbox"
                                                        id="ios">
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="android" class="form-label text-muted">Android</label>
                                                    <input class="form-check-input"
                                                        @if ($data['is_registration']->android == '1') checked @endif type="checkbox"
                                                        id="android">
                                                </div>
                                            </li>

                                        </ol>
                                    </div>

                                    <div class="col-lg-6">
                                        <h4 class="card-title mb-4">Allow screenshot / screen recording</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="ios_screenshot" class="form-label text-muted">iOS</label>
                                                    <input class="form-check-input screenshot"
                                                        @if ($data['is_registration']->ios_screenshot == '0') checked @endif type="checkbox"
                                                        id="ios_screenshot" data-plateform="ios_screenshot">
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="android_screenshot"
                                                        class="form-label text-muted">Android</label>
                                                    <input class="form-check-input screenshot"
                                                        @if ($data['is_registration']->android_screenshot == '0') checked @endif type="checkbox"
                                                        id="android_screenshot" data-plateform="android_screenshot">
                                                </div>
                                            </li>

                                        </ol>
                                    </div>

                                    <div class="col-lg-6 mt-5">
                                        <h4 class="card-title mb-2">Maintenance</h4>
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="android_screenshot"
                                                    class="form-label text-muted">On/Off</label>
                                                    <input class="form-check-input screenshot"
                                                        @if ($data['app_under_maintenance']->maintenance == '1') checked @endif type="checkbox"
                                                        id="maintenance">
                                                </div>
                                            </li>

                                        </ol>
                                    </div>
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

        $('#maintenance').change(function() {

            if ($(this).is(':checked')) {
                var status = true;
            } else {
                var status = false;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('maintenance') }}",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status,
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

        $('.screenshot').change(function() {

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
                    platform_type: $(this).attr('data-plateform')
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
