@extends('layouts.app')
@section('title', 'Create Coupon | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Create Coupon</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">Create Coupon</li>
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
                            <form method="POST" action="{{ route('coupon.store') }}">
                                @csrf
                                <div class="card-text">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Coupon Code</label>
                                        <input type="text" name="code" class="form-control" oninput="this.value = this.value.toUpperCase()" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="coupon_type" class="form-label">Coupon Type</label>
                                        <select name="coupon_type" id="coupon_type" class="form-control" required>
                                            <option value="">Select Option</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed</option>

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Discount</label>
                                        <input type="text" name="discount" class="form-control"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" value=""
                                            required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="coupon_status" class="form-label">Status</label>
                                        <select name="coupon_status" id="coupon_status" class="form-control" required>
                                            <option value="">Select Option</option>
                                            <option value="enabled">Enabled</option>
                                            <option value="disabled">Disabled</option>

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="expire" class="form-label">Expire</label>
                                        <input type="date" name="expire" class="form-control" value="" required />
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary mt-3" type="submit">Create</button>
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
@endpush
