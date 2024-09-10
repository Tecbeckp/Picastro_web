@extends('layouts.app')
@section('title','Help | Picastro')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Help</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                            <li class="breadcrumb-item active">Help</li>
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
                        <form method="POST" action="{{ route('StoreContent') }}">
                            @csrf
                        <div class="card-text">
                            <input type="hidden" name="page_name" value="Help">
                            <textarea class="ckeditor-classic form-control" rows="10" name="content">{!!$help->content ?? '' !!}</textarea>
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
<script src="{{asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
<!-- init js -->
<script src="{{asset('assets/js/pages/form-editor.init.js')}}"></script>
@endpush