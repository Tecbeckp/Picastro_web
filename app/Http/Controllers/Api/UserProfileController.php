<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trophy;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\VoteImage;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    use ApiResponseTrait;
    use UploadImageTrait;

    public function profileSetup(Request $request){
        // return $this->susscess([''],$request->all());
        $rules = [  
            'username'      => 'required|unique:users|max:16|alpha_dash',
            'pronouns'      => 'required',
            'bio'           => 'required|max:120',
            'profile_image' => 'nullable|mimes:jpg,jpeg,png,webp'
        ];
        
        $validator = Validator::make($request->all(), $rules, [
            'username.unique'       => 'The username has already been taken! Try again',
            'username.required'     => 'Username is required.',
            'username.max'          => 'Username must be a maximum of 16 characters.',
            'username.alpha_dash'   => 'The username field must only contain letters, numbers, hyphen, and underscores.',
            'pronouns.required'     => 'Pronouns is required.',
            'bio.required'          => 'Bio is required.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        
        $data = [
            'pronouns'         => $request->pronouns,
            'bio'              => $request->bio,
            'complete_profile' => '1'
        ];

        if ($request->file('profile_image')) {
            $imageName = $this->originalImageUpload($request->file('profile_image'), 'profileImages/');
            $data['profile_image'] = $imageName;
        }
        
        UserProfile::where('user_id', auth()->id())->update($data);

        User::where('id', auth()->id())->update([
            'username' => $request->username,
        ]);
        $user = User::with('userprofile')->where('id', auth()->id())->first();
        $result =[
            'user' => $user
        ];

        return $this->success(['Profile setup Successfully'],$result);
    }

    public function updateProfile(Request $request){
        $rules = [
            'experience_level'  => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'pronouns'          => 'required',
            'bio'               => 'required|max:250',
            'web_site_link'     => 'nullable|url',
            'drive_link'        => 'nullable|url',
            'profile_image'     => 'nullable|mimes:jpg,jpeg,png,webp'
            
        ];
        $validator = Validator::make($request->all(), $rules, [
            'experience_level.required' => 'Experience level is required.',
            'pronouns.required'         => 'Pronouns is required.',
            'bio.required'              => 'bio is required.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $data = [
            'experience_level' => $request->experience_level,
            'pronouns'         => $request->pronouns,
            'bio'              => $request->bio,
            'web_site_one'     => $request->web_site_one,
            'web_site_two'     => $request->web_site_two,
            'web_site_link'    => $request->web_site_link,
            'drive_link'       => $request->drive_link
        ];
        
        if ($request->file('profile_image')) {
            $imageName = $this->imageUpload($request->file('profile_image'), 'profileImages/');
            $data['profile_image'] = $imageName;
        }
        User::where('id',auth()->id())->update(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name
            ]
        );
        
        UserProfile::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            $data
        );

        return $this->success(['Profile Updated Successfully'], []);

    }

    public function getUserProfile(){

        $user = User::with('userprofile')->withCount('TotalStar')->where('id', Auth::id())->first();
        
        $trophies = Trophy::select('id', 'name', 'icon')->get();
        $vote = [];
        
        foreach($trophies as $trophy) {
            $vote[$trophy->id] = VoteImage::where('trophy_id', $trophy->id)
                                           ->where('post_user_id', auth()->id())
                                           ->count();
        }
        
        $data = [
            'user'      => $user,
            'trophies' => $trophies->map(function ($trophy) use ($vote) {
                return [
                    'id' => $trophy->id,
                    'name' => $trophy->name,
                    'icon' => $trophy->icon,
                    'total_trophy' => $vote[$trophy->id] ?? 0
                ];
            })
        ];

        if($user){
            return $this->success(['Successfully Get user profile'], $data);
        }else{
            return $this->error(['Something went wrong']);
        }
    }

}
