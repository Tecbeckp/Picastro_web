<?php

namespace App\Http\Controllers;

use App\Models\PostImage;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PostImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $interval = $request->time;
            $search   = $request->search;
          
            $posts = PostImage::query();
            switch ($interval) {
                case 'Last 7 Days':
                    $posts->where('created_at', '>=', Carbon::now()->subDays(7));
                    break;
                case 'Last 30 Days':
                    $posts->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
                case 'Last Year':
                    $posts->where('created_at', '>=', Carbon::now()->subYear());
                    break;
                case 'This Month':
                    $posts->where('created_at', '>=', Carbon::now()->startOfMonth());
                    break;
                case 'Today':
                    $posts->where('created_at', '>=', Carbon::now()->startOfDay());
                    break;
                case 'Yesterday':
                    $posts->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()]);
                    break;
                default:
                    // No filter applied
                    break;
            }
            if($search){
                $posts->whereAny(['object_name','description'],'LIKE', '%' .$search. '%');
            }
            $posts = $posts->latest()->paginate(10);
            return view('admin.post.posts', compact('posts'))->render();
        }
        $posts = PostImage::latest()->paginate(10);
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
