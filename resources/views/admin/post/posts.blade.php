<div class="row">
    @foreach ($posts as $post)
    <div class="col-xxl-3 col-sm-6 project-card">
        <div class="card" style="height: 400px;"> <!-- Set a fixed height for the card -->
            <div class="card-body p-0">
                <div class="p-0 bg-secondary-subtle rounded-top">
                    <div class="text-center">
                        <img class="card-img-top img-fluid" style="object-fit: cover; width: 100%; height: 300px;" src="{{$post->image}}" alt=""> <!-- Set a fixed height for the image -->
                    </div>
                </div>
                <div class="p-3">
                    <h5 class="fs-14 mb-3"><a href="#" class="text-body">{{ $post->object_name }}</a></h5>
                    <div class="row gy-3">
                        <div class="col-6">
                            <div>
                                <p class="text-muted mb-1">Status</p>
                                <div class="badge bg-success-subtle text-success fs-12">Complete</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div>
                                <p class="text-muted mb-1">Created</p>
                                <h5 class="fs-14">{{ $post->created_at->format('d M, Y') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endforeach
</div>

<div class="row g-0 text-center text-sm-start align-items-center mb-4">
    <div class="col-sm-6">
        <div>
            <p class="mb-sm-0 text-muted">Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} entries</p>
        </div>
    </div>
    <div class="col-sm-6">
        <ul class="pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
            {{-- Previous Page Link --}}
            @if ($posts->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $posts->previousPageUrl() }}">Previous</a></li>
            @endif
    
            {{-- Pagination Elements --}}
            @php
                $start = max($posts->currentPage() - 3, 1);
                $end = min($start + 6, $posts->lastPage());
            @endphp
    
            {{-- "..." before the first page link --}}
            @if ($start > 1)
                <li class="page-item"><span class="page-link">...</span></li>
            @endif
    
            {{-- Links for current page range --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $posts->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor
    
            {{-- "..." after the last page link --}}
            @if ($end < $posts->lastPage())
                <li class="page-item"><span class="page-link">...</span></li>
            @endif
    
            {{-- Next Page Link --}}
            @if ($posts->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $posts->nextPageUrl() }}">Next</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
        </ul>
    </div>
    
    
    
</div>

