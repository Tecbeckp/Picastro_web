@extends('layouts.app')
@section('title', 'Payment Method Status | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Payment Method Status</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">Payment Method Status</li>
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
                            <form method="POST" action="{{ route('updatePaymentStatus') }}">
                                @csrf
                                <div class="card-text">
                                    <div class="row">
                                        {{-- @dd($data); --}}
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="paypal_android" class="form-label text-muted">Paypal Android</label>
                                                    <input class="form-check-input" type="checkbox" @if($data->paypal_android == '1') checked  @endif value="1" id="paypal_android" name="paypal_android">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="stripe_android" class="form-label text-muted">Stripe Android</label>
                                                    <input class="form-check-input" type="checkbox" @if($data->stripe_android == '1') checked  @endif value="1" id="stripe_android" name="stripe_android">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="paypal_ios" class="form-label text-muted">Paypal iOS</label>
                                                    <input class="form-check-input" type="checkbox" @if($data->paypal_ios == '1') checked  @endif value="1" id="paypal_ios" name="paypal_ios">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <label for="stripe_ios" class="form-label text-muted">Stripe iOS</label>
                                                    <input class="form-check-input" type="checkbox" @if($data->stripe_ios == '1') checked  @endif value="1" id="stripe_ios" name="stripe_ios">
                                                </div>
                                            </div>
                                        </div>
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
@endpush
