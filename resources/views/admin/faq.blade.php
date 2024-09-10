@extends('layouts.app')
@section('title', 'Faqs | Picastro')
@section('custom-style')
    <style>

    </style>
@endsection
@section('content')

    <!-- Start::app-content -->
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0" style="text-transform: none !important;">FAQs</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Content Page</a></li>
                                <li class="breadcrumb-item active">faq's</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div></div>
                <div class="btn-list">
                    <a class="btn btn-primary btn-wave me-0 waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#create-faq">
                        <i class="ri-add-line me-1 fw-medium align-middle"></i> Add FAQs
                    </a>
                </div>
            </div>
            <!-- Page Header Close -->


            <!-- Start::row-1 -->
            <div class="row justify-content-center mb-5">

                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        FAQs
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="accordion accordion-customicon1 accordion-primary" id="accordionFAQ3">
                                        @forelse ($faqs as $key => $faq)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingcustomicon{{$key}}">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapsecustomicon{{$key}}"
                                                        aria-expanded="false" aria-controls="collapsecustomicon{{$key}}">
                                                        {{$faq->title}}
                                                    </button>
                                                </h2>
                                                
                                                <!-- Edit and Delete buttons -->
                                                <div class="d-flex justify-content-end my-2">
                                                    <a class="btn btn-sm btn-primary mx-1 edit-faq" data-id="{{$faq->id}}" data-title="{{$faq->title}}" data-detail="{{$faq->description}}" data-status="{{$faq->status}}">Edit</a>
                                                    <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mx-1">Delete</button>
                                                    </form>
                                                </div>
                                    
                                                <div id="collapsecustomicon{{$key}}" class="accordion-collapse collapse"
                                                    aria-labelledby="headingcustomicon{{$key}}" data-bs-parent="#accordionFAQ3">
                                                    <div class="accordion-body">
                                                        {!! $faq->description !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p>No FAQs available.</p>
                                        @endforelse
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--End::row-1 -->

        </div>
    </div>
    <!-- End::app-content -->

    <!-- Start:: Add Company -->
    <div class="modal fade" id="create-faq" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('StoreFaqContent') }}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add FAQ</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <div class="col-xl-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    placeholder="Title">
                            </div>
                            <div class="col-xl-12">
                                <label for="detail" class="form-label">Details</label>
                                <textarea class="ckeditor-classic form-control" rows="5" name="content" placeholder="Details"></textarea>
                            </div>
                            <div class="col-xm-12">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="">--Select--</option>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit-faq" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('faq.edit') }}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Edit FAQ</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <div class="col-xl-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="hidden" name="faq_id" id="faq_id">
                                <input type="text" class="form-control" id="edit_title" name="title" required
                                    placeholder="Title">
                            </div>
                            <div class="col-xl-12">
                                <label for="detail" class="form-label">Details</label>
                                <textarea class="form-control ckeditor-classic-edit" rows="5" id="edit_detail" name="content" placeholder="Details"></textarea>
                            </div>
                            <div class="col-xm-12">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="edit_status" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End:: Add Company -->
@endsection
@push('script')
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <!-- init js -->
    <script src="{{asset('assets/js/pages/form-editor.init.js')}}"></script>

    <script>
        $('.edit-faq').click(function(){
            $('#edit_title').val($(this).attr('data-title'));
           let dataDetail = $(this).attr('data-detail');
           setEditorData('edit_detail', dataDetail); 
           $('#faq_id').val($(this).attr('data-id'));
           let status = $(this).attr('data-status');
            $('#edit_status').html( 
              '<option value="" disabled>Select Option</option>' +
              '<option value="Enable" ' + (status === 'Enable' ? 'selected' : '') + '>Enable</option>' +
              '<option value="Disable" ' + (status === 'Disable' ? 'selected' : '') + '>Disable</option>'
            );

            $('#edit-faq').modal('show'); 
        });
    </script>
@endpush
