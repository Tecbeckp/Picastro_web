<?php

namespace App\Http\Controllers;

use App\Models\PostImage;
use App\Models\StarCamp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.user.index');
    }
    
    public function getAllUser(Request $request){
        $search = $request->search;
        $date   = $request->date;
        $status = $request->status;

        $users = User::query()->with(['userprofile'])->whereNotIn('id',['1']);
        if($search){
            $users->whereAny(['first_name', 'last_name', 'username','email'], 'LIKE', '%'.$search.'%');
        }
        if($date){
            $users->where('created_at', '>=', date('Y-m-d 00:00:00',strtotime($date)))->where('created_at', '<=', date('Y-m-d 23:59:59',strtotime($date)));
        }
        if(!is_null($status)){
            $users->where('status',$status);
        }
        $users->latest();
        // $rowid = 0;
        return DataTables::of($users)->addIndexColumn()
        ->addColumn('ID', function ($row) {
            static $rowid = null;
            static $start = null;
    
            if ($rowid === null) {
                $start = request()->get('start', 0);
                $rowid = $start + 1;
            }
    
            return $rowid++;
        })
            ->addColumn('user', function ($row) {
               return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('username', function ($row) {
                 return $row->username ?? 'N/A';
            })
            ->addColumn('gender', function ($row) {
                return $row->userprofile->pronouns ?? 'N/A';
            })
            ->addColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->created_at));
            })
            ->addColumn('status', function ($row) {
                if($row->status == '1'){
                    $status = '<span class="badge bg-success-subtle text-success text-uppercase">Active</span>';
                }else{
                    $status = '<span class="badge bg-warning-subtle text-warning text-uppercase">Blocked</span>';
                }
                return $status;
            })
                      ->addColumn('action', function ($row) {
                $ID = Crypt::encrypt($row->id);

$statusButton = ($row->status == "1")
    ? '<li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Block">
           <a class="text-danger d-inline-block remove-item-btn" href="block-user/'.$ID.'">
               Block
           </a>
       </li>'
    : '<li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Unblock">
           <a class="text-danger d-inline-block remove-item-btn"  href="unblock-user/'.$ID.'">
               Unblock
           </a>
       </li>';

$btn = '<ul class="list-inline hstack gap-2 mb-0">'
    . $statusButton
    . '<li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
           <a href="users/' . $ID . '" class="text-primary d-inline-block edit-item-btn">
               <i class="ri-eye-fill fs-16"></i>
           </a>
       </li>
       <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
           <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" data-id="' . $ID . '" data-action="users/' . $row->id . '" href="#deleteItem">
               <i class="ri-delete-bin-5-fill fs-16"></i>
           </a>
       </li>
   </ul>';

return $btn;
                
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }


    public function getUserStarCamp(){
        
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
        $user_id = decrypt($id);
        $data = array();
        $data['user'] = User::with('userprofile')->where('id',$user_id)->first();
        $data['total_posts'] = PostImage::where('user_id',$user_id)->count();
        $data['posts'] = PostImage::where('user_id',$user_id)->limit('3')->latest()->get();
        $data['total_starcamp'] = StarCamp::where('created_by',$user_id)->count();
        $data['starcamps'] = StarCamp::where('created_by',$user_id)->get();
        return view('admin.user.detail', compact('data'));
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
    public function blockUser($id){
        $user_id = decrypt($id);

        User::where('id',$user_id)->update([
            'status' => '0'
        ]);

        return redirect('/users')->with(['success' => 'User Blocked successfully']);
    }
     public function unblockUser($id){
        $user_id = decrypt($id);

        User::where('id',$user_id)->update([
            'status' => '1'
        ]);

        return redirect('/users')->with(['success' => 'User Unblock successfully']);
    }
    
    public function blockToUser(){
        
    }
}
