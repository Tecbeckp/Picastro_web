<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FollowerList;
use App\Models\StarCamp;
use App\Models\StarCampMember;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class StarCampController extends Controller
{
    use ApiResponseTrait;
    public function getMemberList(Request $request){
        $search = $request->search;
        if($search){

            $combinedQuery = FollowerList::query()
            ->where(function ($query) use ($search) {
                $query->where('user_id', auth()->id())
                      ->whereHas('follower', function($q) use($search){
                          if ($search) $q->where('username', 'LIKE', "%$search%");
                      });
            })
            ->orWhere(function ($query) use ($search) {
                $query->where('follower_id', auth()->id())
                      ->whereHas('following', function($q) use ($search) {
                          if ($search) $q->where('username', 'LIKE', "%$search%");
                      });
            });
    
        $combinedResults = $combinedQuery->latest()->get();
    
        $combinedData = $combinedResults->map(function($member) {
            return $member->user_id == auth()->id() ? [
                'id'            => $member->follower->id,
                'first_name'    => $member->follower->first_name,
                'last_name'     => $member->follower->last_name,
                'username'      => $member->follower->username,
                'profile_image' => $member->follower->userprofile->profile_image,
                'fcm_token'     => $member->follower->fcm_token
            ] : [
                'id'            => $member->following->id,
                'first_name'    => $member->following->first_name,
                'last_name'     => $member->following->last_name,
                'username'      => $member->following->username,
                'profile_image' => $member->following->userprofile->profile_image,
                'fcm_token'     => $member->following->fcm_token
            ];
        })->unique()->values()->toArray();

                    $unique_data = array_unique($combinedData, SORT_REGULAR);
                    $unique_data = array_values($unique_data);
                    $data = [
                        'members' => $unique_data
                    ];
    
            return $this->success([], $data);
        }else{
            $perPage = $request->get('per_page', 15);
    
            $combinedQuery = FollowerList::query()
                ->where(function ($query){
                    $query->where('user_id', auth()->id());
                })
                ->orWhere(function ($query){
                    $query->where('follower_id', auth()->id());
                });
        
            $combinedResults = $combinedQuery->latest()->get();
        
            $combinedData = $combinedResults->map(function($member) {
                return $member->user_id == auth()->id() ? [
                    'id'            => $member->follower->id,
                    'first_name'    => $member->follower->first_name,
                    'last_name'     => $member->follower->last_name,
                    'username'      => $member->follower->username,
                    'profile_image' => $member->follower->userprofile->profile_image,
                    'fcm_token'     => $member->follower->fcm_token
                ] : [
                    'id'            => $member->following->id,
                    'first_name'    => $member->following->first_name,
                    'last_name'     => $member->following->last_name,
                    'username'      => $member->following->username,
                    'profile_image' => $member->following->userprofile->profile_image,
                    'fcm_token'     => $member->following->fcm_token
                ];
            })->unique()->values()->toArray();
        
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentPageItems = array_slice($combinedData, ($currentPage - 1) * $perPage, $perPage);
            $paginatedResults = new LengthAwarePaginator($currentPageItems, count($combinedData), $perPage, $currentPage, [
                'path' => Paginator::resolveCurrentPath()
            ]);
        
            $paginationData = [
                'current_page' => $paginatedResults->currentPage(),
                'data' => $paginatedResults->items(),
                'first_page_url' => $paginatedResults->url(1),
                'from' => $paginatedResults->firstItem(),
                'last_page' => $paginatedResults->lastPage(),
                'last_page_url' => $paginatedResults->url($paginatedResults->lastPage()),
                'links' => [
                    [
                        'url' => $paginatedResults->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $paginatedResults->currentPage() > 1
                    ],
                    [
                        'url' => $paginatedResults->url($paginatedResults->currentPage()),
                        'label' => (string) $paginatedResults->currentPage(),
                        'active' => true
                    ],
                    [
                        'url' => $paginatedResults->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $paginatedResults->hasMorePages()
                    ]
                ],
                'next_page_url' => $paginatedResults->nextPageUrl(),
                'path' => $paginatedResults->path(),
                'per_page' => $paginatedResults->perPage(),
                'prev_page_url' => $paginatedResults->previousPageUrl(),
                'to' => $paginatedResults->lastItem(),
                'total' => $paginatedResults->total(),
            ];
        
            return $this->success([], $paginationData);
        }
        
    }

    public function getStarcamp(Request $request){
        $perPage = $request->get('per_page', 15);
        
        $starcamps = Starcamp::with('starcampMember.user.userprofile')->where('created_by', auth()->id())->latest()->get();
        $transformedStarcamps = $starcamps->map(function($starcamp) {
            return [
                'id' => $starcamp->id,
                'name' => $starcamp->name,
                'starcamp_member' => $starcamp->starcampMember->map(function($member) {
                    return [
                        'id'            => $member->user->id,
                        'first_name'    => $member->user->first_name,
                        'last_name'     => $member->user->last_name,
                        'username'      => $member->user->username,
                        'profile_image' => $member->user->userprofile->profile_image

                    ];
                })
            ];
        })->toArray();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentPageItems = array_slice($transformedStarcamps, ($currentPage - 1) * $perPage, $perPage);
            $paginatedResults = new LengthAwarePaginator($currentPageItems, count($transformedStarcamps), $perPage, $currentPage, [
                'path' => Paginator::resolveCurrentPath()
            ]);
            $paginationData = [
                'current_page' => $paginatedResults->currentPage(),
                'data' => $paginatedResults->items(),
                'first_page_url' => $paginatedResults->url(1),
                'from' => $paginatedResults->firstItem(),
                'last_page' => $paginatedResults->lastPage(),
                'last_page_url' => $paginatedResults->url($paginatedResults->lastPage()),
                'links' => [
                    [
                        'url' => $paginatedResults->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $paginatedResults->currentPage() > 1
                    ],
                    [
                        'url' => $paginatedResults->url($paginatedResults->currentPage()),
                        'label' => (string) $paginatedResults->currentPage(),
                        'active' => true
                    ],
                    [
                        'url' => $paginatedResults->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $paginatedResults->hasMorePages()
                    ]
                ],
                'next_page_url' => $paginatedResults->nextPageUrl(),
                'path' => $paginatedResults->path(),
                'per_page' => $paginatedResults->perPage(),
                'prev_page_url' => $paginatedResults->previousPageUrl(),
                'to' => $paginatedResults->lastItem(),
                'total' => $paginatedResults->total(),
            ];
            
        return $this->success([], $paginationData);

    }
    public function starcampDetail(Request $request){

        $id = $request->id;
        if($id){
            $starcamps = Starcamp::with('starcampMember.user')->where('id',$id)->where('created_by', auth()->id())->get();
            if($starcamps->isNotEmpty()){
            $transformedStarcamps = $starcamps->map(function($starcamp) {
                return [
                    'id' => $starcamp->id,
                    'name' => $starcamp->name,
                    'starcamp_member' => $starcamp->starcampMember->map(function($member) {
                        return [
                            'id'            => $member->user->id,
                            'first_name'    => $member->user->first_name,
                            'last_name'     => $member->user->last_name,
                            'username'      => $member->user->username,
                            'profile_image' => $member->user->userprofile->profile_image,
                        ];
                    })
                ];
            });
                $data = [
                    'starcamp' => $transformedStarcamps
                ];
                return $this->success([], $data);
            }else{
                return $this->error(['Please enter valid StarCamp id']);
            }
        }else{
            return $this->error(['StarCamp id is required']);
        }
    }

    public function storeStarcamp(Request $request){
        $rules = [
            'name'      => 'required|unique:star_camps|max:25',
            'user_id'   => 'required',
            'user_id.*' => 'numeric|exists:users,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $combinedQuery = FollowerList::query()
        ->where(function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->orWhere(function ($query) {
            $query->where('follower_id', auth()->id());
        });

    $combinedResults = $combinedQuery->latest()->get();

    $combinedData = $combinedResults->map(function($member) {
        return $member->user_id == auth()->id() ? 
            $member->follower->id : $member->following->id;
        })->unique()->values()->toArray();

        $user_ids = json_decode($request->user_id, true);
        $notInArray = array_diff($user_ids, $combinedData);
        if (count($user_ids) <= 0){
            return $this->error(['Please add at least one user to proceed.']);
        }elseif(count($user_ids) > 15){
            return $this->error(['You can add max 15 members.']);
        }elseif(!empty($notInArray)){
            return $this->error(['Some users do not exist in the followings or follower list.']);
        }else{ 
            $starcamp               = new StarCamp();
            $starcamp->created_by   = auth()->id();
            $starcamp->name         = $request->name;
            $starcamp->save();

            foreach($user_ids as $id){
                $starcamp_member                 = new StarCampMember();
                $starcamp_member->star_camp_id   = $starcamp->id;
                $starcamp_member->member_id      = $id;
                $starcamp_member->save();
            }
        } 

        return $this->success(['New StarCamp create successfully!'], []);

    }

    public function updateStarcamp(){
        
    }

    public function deleteStarcamp(Request $request){
        $id = $request->id;
        if($id){
            $StarCamp =  StarCamp::find($id);
            if($StarCamp){
                $StarCamp->delete();
                return $this->success(['StarCamp deleted successfully!'], []);
            }else{
                return $this->error(['Please enter valid Starcamp id']);
            }
        }else{
            return $this->error(['StarCamp id is required']);
        }
    }

    public function removeStarcampMember(Request $request){
        $rules = [
            'star_camp_id' => 'required|numeric|exists:star_camps,id',
            'member_id'    => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $id         = $request->star_camp_id;
        $member_id  = $request->member_id;
        $StarCamp =  StarCampMember::where('star_camp_id',$id)->where('member_id',$member_id)->first();
        if($StarCamp){
            $StarCamp->delete();
            return $this->success(['Member removed from StarCamp successfully!'], []);
        }else{
            return $this->error(['Please enter valid Member id']);
        }
    }

    public function addStarcampMember(Request $request){

        $rules = [
            'star_camp_id' => 'required|numeric|exists:star_camps,id',
            'member_id'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $id         = $request->star_camp_id;
        $member_id  = $request->member_id;
        $members = StarCampMember::where('star_camp_id',$id)->count();

        $combinedQuery = FollowerList::query()
        ->where(function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->orWhere(function ($query) {
            $query->where('follower_id', auth()->id());
        });

    $combinedResults = $combinedQuery->latest()->get();

    $combinedData = $combinedResults->map(function($member) {
        return $member->user_id == auth()->id() ? 
            $member->follower->id : $member->following->id;
        })->unique()->values()->toArray();

        $user_ids     = json_decode($member_id, true);
        $notInArray   = array_diff($user_ids, $combinedData);
        $total_member = $members + count($user_ids);
        $exist_member = StarCampMember::where('star_camp_id',$id)->whereIn('member_id',$user_ids)->first();
        if(empty($user_ids)){
            return $this->error(['The Member id field is required.']);
        }elseif($exist_member){
            return $this->error(['Member already exists in Starcamp.']);
        }elseif($total_member > 15){
            return $this->error(['You can add max 15 members.']);
        }elseif(!empty($notInArray)){
            return $this->error(['Some users do not exist in the followings or follower list.']);
        }else{
            foreach($user_ids as $memberid){
                $starcamp_member                 = new StarCampMember();
                $starcamp_member->star_camp_id   = $id;
                $starcamp_member->member_id      = $memberid;
                $starcamp_member->save();
            }

            return $this->success(['New Member added successfully.'], []);
        }
        
    }
}