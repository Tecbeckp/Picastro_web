@extends('layouts.app')
@section('title', 'Trial Period | Picastro')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Trial Period</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">Trial Period</li>
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
                            <form method="POST" action="{{ route('storeTrialPeriod') }}">
                                @csrf
                                <div class="card-text">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Time</label>
                                        <input type="number" name="time_number"
                                            class="form-control" placeholder="15" min="1" value="{{$data->number}}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Period</label>
                                        <select name="period" id="period" class="form-control" required>
                                            <option value="">Select Option</option>
                                            <option value="minute" @if($data->time_period == 'minute') Selected @endif>Minute</option>
                                            <option value="hour" @if($data->time_period == 'hour') Selected @endif>Hour</option>
                                            <option value="day" @if($data->time_period == 'day') Selected @endif>Day</option>
                                            <option value="week" @if($data->time_period == 'week') Selected @endif>Week</option>
                                            <option value="month" @if($data->time_period == 'month') Selected @endif>Month</option>
                                            <option value="year" @if($data->time_period == 'year') Selected @endif>Year</option>
                                            
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reminder_time" class="form-label">Reminder time</label>
                                        <input type="number" name="reminder_time" id="reminder_time"
                                            class="form-control" placeholder="Reminder time in minutes" min="1" value="{{$data->reminder_time}}" required />
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
