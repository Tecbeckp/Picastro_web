<div class="row">
    @foreach ($posts as $post)
        <div class="col-xxl-4 col-sm-6 project-card">
            <div class="card" style="height: 420px;"> <!-- Set a fixed height for the card -->
                <div class="card-body p-0">
                    <div class="p-0 bg-secondary-subtle rounded-top">
                        <a href="{{ route('posts.show', encrypt($post->id)) }}" class="text-center">
                            <img class="card-img-top img-fluid" style="object-fit: cover; width: 100%; height: 300px;"
                                src="{{ $post->image }}" alt=""> <!-- Set a fixed height for the image -->
                        </a>
                    </div>
                    <div class="p-3">
                        <div class="align-items-center mb-2 justify-content-between">
                            <div class="row gy-3">
                                <div class="col-7">
                                    <h5 class="fs-14 mb-0"><a href="{{ route('posts.show', encrypt($post->id)) }}"
                                            class="text-body">{{ $post->catalogue_number ?? $post->post_image_title }}</a>
                                    </h5>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <p class="text-muted mb-1">Username</p>
                                        <h5 class="fs-14">{{ ucfirst($post->user->username) }}</h5>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="flex-shrink-0">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" role="button" id="dropdownMenuLink2"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-14"></i>
                                            </a>

                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuLink2">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('posts.show', encrypt($post->id)) }}">View</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="deleteConfirmation('{{ encrypt($post->id) }}')">Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row gy-3">
                            <div class="col-4">
                                <div>
                                    <p class="text-muted mb-1">Original Image Size</p>
                                    @php
                                    try {
                                            $parsedUrl = parse_url($post->original_image);
                                            $s3Key = ltrim($parsedUrl['path'], '/');
                                            $fileSizeBytes = Storage::disk('s3')->size($s3Key);
                                            if ($fileSizeBytes) {
                                                $fileSizeMB = $fileSizeBytes / (1024 * 1024);
                                            } else {
                                                $fileSizeMB = 0;
                                            }
                                        } catch (\Exception $e) {
                                            $fileSizeMB = 0;
                                        }
                                    @endphp
                                    <h5 class="fs-14">
                                        {{ round($fileSizeMB, 2) . ' MB' }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <p class="text-muted mb-1">Thumbnail Size</p>
                                    {{-- @dd(explode('/',$post->image)[6]); --}}
                                    @php
                                        $file = explode('/', $post->image);
                                        $filePath = array_slice($file, 7)[0];
                                        $filePath = public_path('assets/uploads/postimage/' . $filePath);
                                        $fileSizeInBytes = filesize($filePath);
                                        $fileSizeInKB = $fileSizeInBytes / 1024;
                                        $fileSizeInMB = $fileSizeInKB / 1024;
                                    @endphp
                                    <h5 class="fs-14">
                                        @if ($fileSizeInKB >= '1024')
                                            {{ round($fileSizeInMB, 2) . ' MB' }}
                                        @else
                                            {{ round($fileSizeInKB, 2) . ' KB' }}
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4">
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
            <p class="mb-sm-0 text-muted">Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of
                {{ $posts->total() }} entries</p>
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
                    <li class="page-item"><a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                    </li>
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
