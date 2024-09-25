<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\Report;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PostImageController extends Controller
{
    use ApiResponseTrait;
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
                $posts->whereAny(['object_name','catalogue_number','post_image_title','description'],'LIKE', '%' .$search. '%');
            }
            $posts = $posts->with('user')->latest()->paginate(10);
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
        $post_id = decrypt($id);

        $post =  PostImage::with('user','Follower.follower.userProfile')->where('id',$post_id)->first();
        $post_comments = PostComment::with('user.userprofile','ReplyComment.user.userprofile')->whereNull('post_comment_id')->where('post_image_id',$post_id)->latest()->get();
        $reports = Report::with('user')->where('post_image_id',$post_id)->get();
        return view('admin.post.detail', compact('post','post_comments','reports'));
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
       $data = PostImage::find(decrypt($id));

       if($data){
            $data->delete();
            return $this->success('Post deleted successfully!', []);
       }else{
        return $this->error('Something went wrong please try again');
       }
        
    }
}
