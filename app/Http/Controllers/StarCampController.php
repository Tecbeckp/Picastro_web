<?php

namespace App\Http\Controllers;

use App\Models\StarCamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class StarCampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.starcamp.index');
    }

    public function getAllstarcamp(Request $request){
        $search = $request->search;
        $date   = $request->date;

        $starcamps = StarCamp::query()->with(['starcampMember.user']);
        if($search){
            $starcamps->where('name', 'LIKE', '%'.$search.'%');
        }
        if($date){
            $starcamps->where('created_at', '>=', date('Y-m-d 00:00:00',strtotime($date)))->where('created_at', '<=', date('Y-m-d 23:59:59',strtotime($date)));
        }
        $starcamps->latest();
        $rowid = 0;

        return DataTables::of($starcamps)->addIndexColumn()
        ->addColumn('ID', function ($row) use(&$rowid) {
            $rowid++;
            return $rowid;
         })
            ->addColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->created_at));
            })
            ->addColumn('member', function ($row) {
                if($row->starcampMember->isNotEmpty()){
                   $btn = '<div class="avatar-group">';
                    foreach($row->starcampMember as $member){
                        $btn .= '<a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="'.$member->user->username.'">
                        <img src="'.$member->user->userprofile->profile_image.'" alt="" class="rounded-circle avatar-xxs" />
                    </a>';
                    }
                    $btn .= '</div>';
                }else{
                    $btn = 'N/A';
                }
                return $btn;
            })
            ->addColumn('action', function ($row) {
                $ID  = Crypt::encrypt($row->id);
                $btn = '<ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                    <a href="starcamps/' . $ID .'" class="text-primary d-inline-block edit-item-btn">
                                                        <i class="ri-eye-fill fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" data-id="' . $ID . '" data-action="users/' . $row->id . '" href="#deleteCamp">
                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                    </a>
                                                </li>
                                            </ul>';
                return $btn;
            })
            ->rawColumns(['action', 'member'])
            ->make(true);
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
        $starcamp_id = decrypt($id);
        $starcamp = StarCamp::with('user','starcampMember.user.userProfile','starcampMember.Post','starcampMember.memberStarcamp')->where('id',$starcamp_id)->first();
        return view('admin.starcamp.detail', compact('starcamp'));

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
