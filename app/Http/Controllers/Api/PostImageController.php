<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApproxLunarPhase;
use App\Models\Bortle;
use App\Models\MainSetup;
use App\Models\ObjectType;
use App\Models\ObserverLocation;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\StarCard;
use App\Models\StarCardFilter;
use App\Models\Telescope;
use App\Models\Trophy;
use App\Models\User;
use App\Models\VoteImage;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostImageController extends Controller
{
    use ApiResponseTrait;
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $followers = FollowerList::with(['follower' => function($query) {
        //     $query->select('id', 'username');
        // }])->where('user_id',auth()->id())->latest()->get();

        // $following = FollowerList::with(['following' => function($query) {
        //     $query->select('id', 'username');
        // }])->orwhere('follower_id',auth()->id())->latest()->get();

       
        //         $follower_data = $followers->map(function($member) {
        //             return $member->follower->id;
        //         })->toArray();

        //         $following_data = $following->map(function($member) {
        //             return $member->following->id;
        //         })->toArray();

        //         $merged_data = array_merge($follower_data, $following_data, [auth()->id()]);
        //         $unique_data = array_unique($merged_data, SORT_REGULAR);
        //         $user_ids = array_values($unique_data);
                
        $posts = PostImage::with('user','StarCard.StarCardFilter','ObjectType','Bortle','ObserverLocation','ApproxLunarPhase','Telescope','giveStar','totalStar','Follow','votedTrophy')->where('user_id',auth()->id())->latest()->paginate(10);
        $trophies = Trophy::select('id','name','icon')->get();
        $posts->getCollection()->transform(function ($post) use($trophies) {
            return [
                'id'                 => $post->id,
                'user_id'            => $post->user_id,
                'post_image_title'   => $post->post_image_title,
                'image'              => $post->image,
                'description'        => $post->description,
                'video_length'       => $post->video_length,
                'number_of_frame'    => $post->number_of_frame,
                'number_of_video'    => $post->number_of_video,
                'exposure_time'      => $post->exposure_time,
                'total_hours'        => $post->total_hours,
                'additional_minutes' => $post->additional_minutes,
                'catalogue_number'   => $post->catalogue_number,
                'object_name'        => $post->object_name,
                'ir_pass'            => $post->ir_pass,
                'planet_name'        => $post->planet_name,
                'location'           => $post->location,
                'ObjectType'         => $post->ObjectType,
                'Bortle'             => $post->Bortle,
                'ObserverLocation'   => $post->ObserverLocation,
                'ApproxLunarPhase'   => $post->ApproxLunarPhase,
                'Telescope'          => $post->Telescope,
                'only_image_and_description' => $post->only_image_and_description,
                'giveStar'           => $post->giveStar ? true : false,
                'totalStar'          => $post->totalStar ? $post->totalStar->count() : 0,
                'Follow'             => $post->Follow ? true : false,
                'voted_trophy_id'    => $post->votedTrophy ? $post->votedTrophy->trophy_id : null,
                'trophy'             => $trophies,
                'star_card'           => $post->StarCard,
                'user'               => [
                    'id'             => $post->user->id,
                    'first_name'     => $post->user->first_name,
                    'last_name'      => $post->user->last_name,
                    'username'       => $post->user->username,
                    'profile_image'  => $post->user->userprofile->profile_image,
                    'fcm_token'      => $post->user->fcm_token,
                ]
            ];
        });
        return $this->success([], $posts);
    }

    public function allPostImage(Request $request)
    {         
        $rules = [
            'location'          => 'nullable',
            'telescope_type_id' => 'nullable|numeric|exists:telescopes,id',
            'object_type_id'    => 'nullable|numeric|exists:object_types,id',
            'most_recent'       => 'nullable|numeric|exists:object_types,id',
            'randomizer'        => 'nullable|numeric|exists:object_types,id'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $location       = $request->location;
        $telescope_type = $request->telescope_type_id;
        $object_type    = $request->object_type_id;
        $most_recent    = $request->most_recent;
        $randomizer    = $request->randomizer;
        if($location && $location == 'NH'){
          $observer_location = [1, 2, 3, 4, 6];
        }elseif($location && $location == 'SH'){
          $observer_location = [5, 7, 8];
        }else{
            $observer_location = null;
        }

        $posts = PostImage::with('user','StarCard.StarCardFilter','ObjectType','Bortle','ObserverLocation','ApproxLunarPhase','Telescope','giveStar','totalStar','Follow','votedTrophy')->whereDoesntHave('blockToUser')->whereNot('user_id',auth()->id());
        if($observer_location){
            $posts->whereIn('observer_location_id',$observer_location);
        }
        if($object_type){
            $posts->where('object_type_id',$object_type);
        }
        if($telescope_type){
            $posts->where('telescope_id', $telescope_type);
        }
        if($randomizer){
            $posts->where('object_type_id',$randomizer)->inRandomOrder();
        }
        if($most_recent){
            $posts->where('observer_location_id',$most_recent);
        }
       $posts = $posts->latest()->paginate(10);
        $trophies = Trophy::select('id','name','icon')->get();
        $posts->getCollection()->transform(function ($post) use ($trophies) {

            return [
                'id'                 => $post->id,
                'user_id'            => $post->user_id,
                'post_image_title'   => $post->post_image_title,
                'image'              => $post->image,
                'description'        => $post->description,
                'video_length'       => $post->video_length,
                'number_of_frame'    => $post->number_of_frame,
                'number_of_video'    => $post->number_of_video,
                'exposure_time'      => $post->exposure_time,
                'total_hours'        => $post->total_hours,
                'additional_minutes' => $post->additional_minutes,
                'catalogue_number'   => $post->catalogue_number,
                'object_name'        => $post->object_name,
                'ir_pass'            => $post->ir_pass,
                'planet_name'        => $post->planet_name,
                'ObjectType'         => $post->ObjectType,
                'Bortle'             => $post->Bortle,
                'ObserverLocation'   => $post->ObserverLocation,
                'ApproxLunarPhase'   => $post->ApproxLunarPhase,
                'location'           => $post->location,
                'Telescope'          => $post->Telescope,
                'only_image_and_description' => $post->only_image_and_description,
                'giveStar'           => $post->giveStar ? true : false,
                'totalStar'          => $post->totalStar ? $post->totalStar->count() : 0,
                'Follow'             => $post->Follow ? true : false,
                'voted_trophy_id'    => $post->votedTrophy ? $post->votedTrophy->trophy_id : null,
                'trophy'             => $trophies,
                'star_card'          => $post->StarCard,
                'user'               => [
                    'id'             => $post->user->id,
                    'first_name'     => $post->user->first_name,
                    'last_name'      => $post->user->last_name,
                    'username'       => $post->user->username,
                    'profile_image'  => $post->user->userprofile->profile_image,
                    'fcm_token'      => $post->user->fcm_token,
                ]
            ];
        });
        return $this->success([], $posts);
    }

    public function userPostImage(Request $request)
    {
        $rules = [
            'user_id'   => 'required|numeric|exists:users,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $user = User::with('userprofile')->withCount('TotalStar')->where('id', $request->user_id)->first();
        $trophies = Trophy::select('id', 'name', 'icon')->get();
        $vote = [];
        foreach($trophies as $trophy) {
            $vote[$trophy->id] = VoteImage::where('trophy_id', $trophy->id)
                                           ->where('post_user_id', $request->user_id)
                                           ->count();
        }
       

        $posts = PostImage::with('user','StarCard.StarCardFilter','ObjectType','Bortle','ObserverLocation','ApproxLunarPhase','Telescope','giveStar','totalStar','Follow','votedTrophy')->where('user_id',$request->user_id)->latest()->paginate(10);
        $troph = Trophy::select('id','name','icon')->get();
        $data = [
            'user' => $user,
            'trophies' => $trophies->map(function ($trophy) use ($vote) {
            return [
                'user_id' => $trophy->id,
                'user_name' => $trophy->name,
                'user_icon' => $trophy->icon,
                'user_total_trophy' => $vote[$trophy->id] ?? 0
            ];
        }),
        'user_post' => $posts->getCollection()->transform(function ($post) use($troph) {
            return [
                'id'                 => $post->id,
                'user_id'            => $post->user_id,
                'post_image_title'   => $post->post_image_title,
                'image'              => $post->image,
                'description'        => $post->description,
                'video_length'       => $post->video_length,
                'number_of_frame'    => $post->number_of_frame,
                'number_of_video'    => $post->number_of_video,
                'exposure_time'      => $post->exposure_time,
                'total_hours'        => $post->total_hours,
                'additional_minutes' => $post->additional_minutes,
                'catalogue_number'   => $post->catalogue_number,
                'object_name'        => $post->object_name,
                'ir_pass'            => $post->ir_pass,
                'planet_name'        => $post->planet_name,
                'location'           => $post->location,
                'ObjectType'         => $post->ObjectType,
                'Bortle'             => $post->Bortle,
                'ObserverLocation'   => $post->ObserverLocation,
                'ApproxLunarPhase'   => $post->ApproxLunarPhase,
                'Telescope'          => $post->Telescope,
                'only_image_and_description' => $post->only_image_and_description,
                'giveStar'           => $post->giveStar ? true : false,
                'totalStar'          => $post->totalStar ? $post->totalStar->count() : 0,
                'Follow'             => $post->Follow ? true : false,
                'voted_trophy_id'    => $post->votedTrophy ? $post->votedTrophy->trophy_id : null,
                'trophy'             => $troph,
                'star_card'          => $post->StarCard,
                'user'               => [
                    'id'             => $post->user->id,
                    'first_name'     => $post->user->first_name,
                    'last_name'      => $post->user->last_name,
                    'username'       => $post->user->username,
                    'profile_image'  => $post->user->userprofile->profile_image,
                    'fcm_token'      => $post->user->fcm_token,
                ]
            ];
        })  
        ];
        return $this->success([], $data);
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
        $rules = [
            'image'                 => 'required|mimes:jpg,jpeg,png,webp,tiff|max:153600',
            'description'           => 'required',
            'object_type'           => 'required_if:only_image_and_description,false',
            'bortle_number'         => 'required_if:only_image_and_description,false',
            'observer_location'     => 'required_if:only_image_and_description,false',
            'approx_lunar_phase'    => 'required_if:only_image_and_description,false',
            'telescope'             => 'required_if:only_image_and_description,false',
            'post_image_title'      => 'required_if:only_image_and_description,true',
            'add_startcard'         => 'required'
        ];
        if($request->only_image_and_description == 'false'){

            if($request->object_type != '7' && $request->object_type != '8' && $request->object_type != '10'){

                $rules['total_hours']           = 'required|numeric|min:0';
                $rules['additional_minutes']    = 'required|numeric|min:0';
                if($request->object_type != '4' && $request->object_type != '9'){
                    $rules['catalogue_number']      = 'required';
                    $rules['object_common_name']    = 'required';
                }

            }
            if ($request->object_type == '7' || $request->object_type == '8' || $request->object_type == '10') {

                $rules['video_length']     = 'required|numeric|min:0';
                $rules['number_of_frame']  = 'required|numeric|min:0';
                $rules['number_of_video']  = 'required|numeric|min:0';
                $rules['total_exposure_time']    = 'nullable|numeric|min:0';
                $rules['ir_pass']          = 'required';
                $rules['planet_name']      = 'required_if:object_type,10';
            }

            // if($request->add_startcard == 'true'){

                // $rules['camera_type']           = 'required';
                // // $rules['setup']                 = 'required';
                // $rules['number_of_darks']       = 'required|numeric|min:0';
                // $rules['number_of_flats']       = 'required|numeric|min:0';
                // $rules['number_of_dark_flats']  = 'required|numeric|min:0';
                // $rules['number_of_bias']        = 'required|numeric|min:0';
                
                // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
                //     $rules['light_frame_number']    = 'required|numeric|min:0';
                //     $rules['light_frame_exposure']  = 'required|numeric|min:0';
                // }
                // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
                //     $rules['cooling']    = 'required|numeric';
                // }
                // if (strtolower($request->camera_type) == 'dslr') {
                //     $rules['iso']           = 'required|numeric|min:0';
                //     $rules['ratio']         = 'required';
                //     $rules['focal_length']  = 'required';
                // }
                // if (strtolower($request->camera_type) == 'mono camera') {
                //     $rules['filter_name']     = 'required';
                //     $rules['number_of_subs']  = 'required';
                //     $rules['exposure_time']   = 'required';
                //     $rules['gain']            = 'required';
                //     $rules['binning']         = 'required';
                // }
            // }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        try {
            $imageName =  $this->imageUpload($request->file('image'), 'images/');

            $postImage                        = new PostImage();
            $postImage->user_id               = auth()->id();
            $postImage->image                 = $imageName;
            $postImage->description           = $request->description;
        if($request->only_image_and_description == 'false'){

            $postImage->object_type_id        = $request->object_type;
            $postImage->bortle_id             = $request->bortle_number;
            $postImage->observer_location_id  = $request->observer_location;
            $postImage->approx_lunar_phase_id = $request->approx_lunar_phase;
            $postImage->telescope_id          = $request->telescope;

            if($request->object_type == '7' || $request->object_type == '8' || $request->object_type == '10') {
            $postImage->video_length    = $request->video_length;
            $postImage->number_of_frame = $request->number_of_frame;
            $postImage->number_of_video = $request->number_of_video;
            $postImage->exposure_time   = $request->total_exposure_time;
            $postImage->ir_pass         = $request->ir_pass;
            }
            if($request->object_type == '10'){
            $postImage->planet_name  = $request->planet_name;
            }

            if($request->object_type != '7' && $request->object_type != '8' && $request->object_type != '10'){
                $postImage->total_hours         = $request->total_hours;
                $postImage->additional_minutes  = $request->additional_minutes;
                if($request->object_type != '4' && $request->object_type != '9'){
                    $postImage->catalogue_number    = $request->catalogue_number;
                    $postImage->object_name         = $request->object_common_name;
                }
            }  
        }else{
            $postImage->post_image_title    = $request->post_image_title;
        }
            $postImage->only_image_and_description = $request->only_image_and_description == 'false' ? '0' : '1' ;
            $postImage->save();

            if($request->add_startcard == 'true'){
                
                $starcard                       = new StarCard();
                $starcard->user_id              = auth()->id();
                $starcard->post_image_id        = $postImage->id;
                $starcard->camera_type          = $request->camera_type;
                $starcard->setup                = $request->setup;
                $starcard->number_of_darks      = $request->number_of_darks;
                $starcard->number_of_flats      = $request->number_of_flats;
                $starcard->number_of_dark_flats = $request->number_of_dark_flats;
                $starcard->number_of_bias       = $request->number_of_bias;
                if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
                    
                $starcard->light_frame_number    = $request->light_frame_number;
                $starcard->light_frame_exposure  = $request->light_frame_exposure;
                }
                if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
                    $starcard->cooling    = $request->cooling;
                }
                if (strtolower($request->camera_type) == 'dslr') {
                $starcard->iso          = $request->iso;
                $starcard->ratio        = $request->ratio;
                $starcard->focal_length = $request->focal_length;
                }
                $main_setup = MainSetup::where('id',$request->setup)->where('user_id',auth()->id())->first();
                if($main_setup){
                    $starcard->telescope_name = $main_setup->telescope_name;
                    $starcard->scope_type = $main_setup->scope_type;
                    $starcard->mount_name = $main_setup->mount_name;
                    $starcard->camera_lens = $main_setup->camera_lens;
                    $starcard->imaging_camera = $main_setup->imaging_camera;
                    $starcard->guide_camera = $main_setup->guide_camera;
                    $starcard->guide_scope = $main_setup->guide_scope;
                    $starcard->filter_wheel = $main_setup->filter_wheel;
                    $starcard->reducer_name = $main_setup->reducer_name;
                    $starcard->autofocuser = $main_setup->autofocuser;
                    $starcard->other_accessories = $main_setup->other_accessories;
                    $starcard->barlow_lens = $main_setup->barlow_lens;
                    $starcard->filters = $main_setup->filters;
                    $starcard->acquistion_software = $main_setup->acquistion_software;
                    $starcard->processing = $main_setup->processing;
                }
                $starcard->save();

                if (strtolower($request->camera_type) == 'mono camera') {
                    if($request->filter_name){
                $filter_names = json_decode($request->filter_name, true);
                $number_of_subs = json_decode($request->number_of_subs, true);
                $exposure_times = json_decode($request->exposure_time, true);
                $gains = json_decode($request->gain, true);
                $binnings = json_decode($request->binning, true);
            
                    foreach ($filter_names as $index => $filter_name) {
                        $setup_filter                 = new StarCardFilter();
                        $setup_filter->star_card_id   = $starcard->id;
                        $setup_filter->name           = $filter_name;
                        $setup_filter->number_of_subs = $number_of_subs[$index];
                        $setup_filter->exposure_time  = $exposure_times[$index];
                        $setup_filter->gain           = $gains[$index];
                        $setup_filter->binning        = $binnings[$index];
                        $setup_filter->save();
                    }
                    }
                }
            }

            return $this->success(['Post uploaded successfully!'], []);

        } catch (ValidationException $e) {
            return $this->error($e->errors());
        }
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

        $posts = PostImage::with('user','StarCard.StarCardFilter','ObjectType','Bortle','ObserverLocation','ApproxLunarPhase','Telescope','giveStar','totalStar','Follow','votedTrophy')->where('user_id',auth()->id())->where('id',$id)->get();
        $trophies = Trophy::select('id','name','icon')->get();
        $posts->transform(function ($post) use($trophies) {
            return [
                'id'                 => $post->id,
                'user_id'            => $post->user_id,
                'post_image_title'   => $post->post_image_title,
                'image'              => $post->image,
                'description'        => $post->description,
                'video_length'       => $post->video_length,
                'number_of_frame'    => $post->number_of_frame,
                'number_of_video'    => $post->number_of_video,
                'exposure_time'      => $post->exposure_time,
                'total_hours'        => $post->total_hours,
                'additional_minutes' => $post->additional_minutes,
                'catalogue_number'   => $post->catalogue_number,
                'object_name'        => $post->object_name,
                'ir_pass'            => $post->ir_pass,
                'planet_name'        => $post->planet_name,
                'location'           => $post->location,
                'ObjectType'         => $post->ObjectType,
                'Bortle'             => $post->Bortle,
                'ObserverLocation'   => $post->ObserverLocation,
                'ApproxLunarPhase'   => $post->ApproxLunarPhase,
                'Telescope'          => $post->Telescope,
                'only_image_and_description' => $post->only_image_and_description,
                'giveStar'           => $post->giveStar ? true : false,
                'totalStar'          => $post->totalStar ? $post->totalStar->count() : 0,
                'Follow'             => $post->Follow ? true : false,
                'voted_trophy_id'    => $post->votedTrophy ? $post->votedTrophy->trophy_id : null,
                'trophy'             => $trophies,
                'star_card'           => $post->StarCard,
                'user'               => [
                    'id'             => $post->user->id,
                    'first_name'     => $post->user->first_name,
                    'last_name'      => $post->user->last_name,
                    'username'       => $post->user->username,
                    'profile_image'  => $post->user->userprofile->profile_image,
                    'fcm_token'      => $post->user->fcm_token,
                ]
            ];
        });
        return $this->success([], $posts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $rules = [
            'image'                 => 'nullable|mimes:jpg,jpeg,png,webp,tiff|max:153600',
            'description'           => 'required',
            'object_type'           => 'required_if:only_image_and_description,false',
            'bortle_number'         => 'required_if:only_image_and_description,false',
            'observer_location'     => 'required_if:only_image_and_description,false',
            'approx_lunar_phase'    => 'required_if:only_image_and_description,false',
            'telescope'             => 'required_if:only_image_and_description,false',
            'post_image_title'      => 'required_if:only_image_and_description,true',
            'add_startcard'         => 'required'
        ];

        if($request->only_image_and_description == 'false'){

            if($request->object_type != '7' && $request->object_type != '8' && $request->object_type != '10'){

                $rules['total_hours']           = 'required|numeric|min:0';
                $rules['additional_minutes']    = 'required|numeric|min:0';
                if($request->object_type != '4' && $request->object_type != '9'){
                    $rules['catalogue_number']      = 'required';
                    $rules['object_common_name']    = 'required';
                }

            }
            if ($request->object_type == '7' || $request->object_type == '8' || $request->object_type == '10') {

               $rules['video_length']     = 'required|numeric|min:0';
                $rules['number_of_frame']  = 'required|numeric|min:0';
                $rules['number_of_video']  = 'required|numeric|min:0';
                $rules['total_exposure_time'] = 'required|numeric|min:0';
                $rules['ir_pass']          = 'required';
                $rules['planet_name']      = 'required_if:object_type,10';
            }
            
            

            // if($request->add_startcard == 'true'){

            //     $rules['camera_type']           = 'required';
            //     // $rules['setup']                 = 'required';
            //     $rules['number_of_darks']       = 'required|numeric|min:0';
            //     $rules['number_of_flats']       = 'required|numeric|min:0';
            //     $rules['number_of_dark_flats']  = 'required|numeric|min:0';
            //     $rules['number_of_bias']        = 'required|numeric|min:0';
                
            //     if (strtolower($request->camera_type) == 'osc Camera' || strtolower($request->camera_type) == 'dslr') {
            //         $rules['light_frame_number']    = 'required|numeric|min:0';
            //         $rules['light_frame_exposure']  = 'required|numeric|min:0';
            //     }
            //     if (strtolower($request->camera_type) == 'osc Camera' || strtolower($request->camera_type) == 'mono camera') {
            //         $rules['cooling']    = 'required|numeric';
            //     }
            //     if (strtolower($request->camera_type) == 'dslr') {
            //         $rules['iso']           = 'required|numeric|min:0';
            //         $rules['ratio']         = 'required';
            //         $rules['focal_length']  = 'required';
            //     }
                // if (strtolower($request->camera_type) == 'mono camera') {
                //     $rules['filter_name']     = 'required';
                //     $rules['number_of_subs']  = 'required';
                //     $rules['exposure_time']   = 'required';
                //     $rules['gain']            = 'required';
                //     $rules['binning']         = 'required';
                // }
            // }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $id = $request->id;
        $post = PostImage::where('user_id',auth()->id())->where('id',$id)->first();
       
        $tableName = 'post_images';
        $uniqueId = $id; // Replace with the actual unique ID or value

        // Fetch all column names for the table
        $columns = Schema::getColumnListing($tableName);

        // Prepare the array to update columns to NULL
        $updateData = array_fill_keys($columns, null);

        // Remove the unique ID column from update data to prevent nullifying it
        unset($updateData['id']); // Replace 'id' with the actual unique column name if different
        unset($updateData['user_id']);
        unset($updateData['image']);
        unset($updateData['created_at']);
    
        // Update the specific row identified by the unique ID
        DB::table($tableName)->where('id', $uniqueId)->update($updateData);
        
          $tableNameStar = 'star_cards';
        $uniqueIdStar = $id; // Replace with the actual unique ID or value

        // Fetch all column names for the table
        $columnsStar = Schema::getColumnListing($tableNameStar);

        // Prepare the array to update columns to NULL
        $updateDataStar = array_fill_keys($columnsStar, null);

        // Remove the unique ID column from update data to prevent nullifying it
        unset($updateDataStar['id']); // Replace 'id' with the actual unique column name if different
        unset($updateDataStar['user_id']);
        unset($updateDataStar['post_image_id']);
        unset($updateDataStar['created_at']);
    
        // Update the specific row identified by the unique ID
        DB::table($tableNameStar)->where('post_image_id', $uniqueIdStar)->update($updateDataStar);

        if($post){
            if($request->file('image')){
                $imageName =  $this->imageUpload($request->file('image'), 'images/');
            }

            $data = [
                'object_type_id'        => $request->object_type,
                'bortle_id'             => $request->bortle_number,
                'observer_location_id'  => $request->observer_location,
                'approx_lunar_phase_id' => $request->approx_lunar_phase,
                'telescope_id'          => $request->telescope,
                'description'           => $request->description,

            ];
            if($request->file('image')){
                $data['image']                 = $imageName;
            }

        if($request->only_image_and_description == 'false'){

          if($request->object_type != '7' && $request->object_type != '8' && $request->object_type != '10'){

                $data['total_hours']           = $request->total_hours;
                $data['additional_minutes']    = $request->additional_minutes;
                if($request->object_type != '4' && $request->object_type != '9'){
                    $data['catalogue_number']      = $request->catalogue_number;
                    $data['object_name']           = $request->object_common_name;
                }
                

            }
            if ($request->object_type == '7' || $request->object_type == '8' || $request->object_type == '10') {
            $data['video_length']    = $request->video_length;
            $data['number_of_frame'] = $request->number_of_frame;
            $data['number_of_video'] = $request->number_of_video;
            $data['exposure_time']   = $request->total_exposure_time;
            $data['ir_pass']         = $request->ir_pass;
            }

            if($request->object_type == '10'){
            $data['planet_name']  = $request->planet_name;
            }   
        }else{
            $data['post_image_title']  = $request->post_image_title;
        }
        $data['only_image_and_description'] = $request->only_image_and_description == 'false' ? '0' : '1' ;
        
            PostImage::where('user_id',auth()->id())->where('id',$id)->update($data);

            if($request->add_startcard == 'true'){
                $data = [
                    'camera_type'           => $request->camera_type,
                    'setup'                 => $request->setup,
                    'number_of_darks'       => $request->number_of_darks,
                    'number_of_flats'       => $request->number_of_flats,
                    'number_of_dark_flats'  => $request->number_of_dark_flats,
                    'number_of_bias'        => $request->number_of_bias,
                ];
                if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
                    $data['light_frame_number']   = $request->light_frame_number;
                    $data['light_frame_exposure'] = $request->light_frame_exposure;
                }
                if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
                    $data['cooling']    = $request->cooling;
                }
                if (strtolower($request->camera_type) == 'dslr') {
                    $data['iso']          = $request->iso;
                    $data['ratio']        = $request->ratio;
                    $data['focal_length'] = $request->focal_length;
                }
                $main_setup = MainSetup::where('id',$request->setup)->where('user_id',auth()->id())->first();
                if($main_setup){
                $data['telescope_name']       = $main_setup->telescope_name;
                $data['scope_type']           = $main_setup->scope_type;
                $data['mount_name']           = $main_setup->mount_name;
                $data['camera_lens']          = $main_setup->camera_lens;
                $data['imaging_camera']       = $main_setup->imaging_camera;
                $data['guide_camera']         = $main_setup->guide_camera;
                $data['guide_scope']          = $main_setup->guide_scope;
                $data['filter_wheel']         = $main_setup->filter_wheel;
                $data['reducer_name']         = $main_setup->reducer_name;
                $data['autofocuser']          = $main_setup->autofocuser;
                $data['other_accessories']    = $main_setup->other_accessories;
                $data['barlow_lens']          = $main_setup->barlow_lens;
                $data['filters']              = $main_setup->filters;
                $data['acquistion_software']  = $main_setup->acquistion_software;
                $data['processing']           = $main_setup->processing;
                }
                     StarCard::updateOrCreate(
                        [
                            'user_id'       => auth()->id(),
                            'post_image_id' => $id            
                        ],
                            $data
                        );

              $starcard =  StarCard::where('user_id',auth()->id())->where('post_image_id',$id)->first();
                          $filter =  StarCardFilter::where('star_card_id',$starcard->id)->first();
                          if($filter){
                              $filter->forceDelete();
                          }
                if (strtolower($request->camera_type) == 'mono camera') {
                    if($request->filter_name){
                    $filter_names   = json_decode($request->filter_name, true);
                    $number_of_subs = json_decode($request->number_of_subs, true);
                    $exposure_times = json_decode($request->exposure_time, true);
                    $gains          = json_decode($request->gain, true);
                    $binnings       = json_decode($request->binning, true);
                    
                    foreach($filter_names as $index => $filter_name){
                        StarCardFilter::updateOrCreate([
                            'star_card_id'   => $starcard->id,
                            'name'           => $filter_name
                        ],[
                        'number_of_subs' => $number_of_subs[$index],
                        'exposure_time'  => $exposure_times[$index],
                        'gain'           => $gains[$index],
                        'binning'        => $binnings[$index]
                        ]);
                    }
                }
                }
            }else{
                $starcard =  StarCard::where('user_id',auth()->id())->where('post_image_id',$id)->first();
                if($starcard){
                    $starcard->forceDelete();
                   $filter =  StarCardFilter::where('star_card_id',$starcard->id)->first();
                          if($filter){
                              $filter->forceDelete();
                          } 
                }
                          
            }

            return $this->success(['Post updated successfully!'], []);

        }else{
            return $this->error(['Please enter valid post id']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        if($id){
            $PostImage =  PostImage::find($id);
            if($PostImage){
                $PostImage->delete();
                return $this->success(['Post deleted successfully!'], []);
            }else{
                return $this->error(['Please enter valid post id']);
            }
        }else{
            return $this->error(['Post id is required']);
        }    
    }

    public function GetObjectInfo()
    {
        $data = array();

        $data['object_types']        = ObjectType::select('id','name','icon')->get();
        $data['observer_locations']  = ObserverLocation::select('id','name')->get();
        $data['bortles']             = Bortle::select('id','bortle_number as name')->get();
        $data['approx_lunar_phases'] = ApproxLunarPhase::select('id','number as name')->get();
        $data['telescopes']          = Telescope::select('id','name','icon')->get();

        return $this->success(['successfully get Object info list'], $data);

    }

}