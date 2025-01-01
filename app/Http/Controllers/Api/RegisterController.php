<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IsRegistration;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\ValidEmail;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use ApiResponseTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        $rules = [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'platform_type'     => 'required|string',
            'email'             => 'required|email',
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.required'             => 'Email Address is required.',
            'email.email'                => 'Please enter a valid email address.',
            'password.required'          => 'Password is required.',
            'confirm_password.required'  => 'confirm password is required.',
            'password.min'               => 'Password must be atleast 8 characters long.',

        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $is_register = IsRegistration::where('id', 1)->first();

        if (isset($is_register) && $is_register->android == '0' && $request->platform_type == 'android') {
            return $this->error(['We are not accepting registrations at the moment.']);
        } elseif (isset($is_register) && $is_register->ios == '0' && $request->platform_type == 'iOS') {
            return $this->error(['We are not accepting registrations at the moment.']);
        }
        $new_user = User::where('email', $request->email)->first();
        if ($new_user && $new_user->is_registration == '1') {
            return $this->error(['Email has already been taken.']);
        } elseif ($new_user && $new_user->is_registration == '0') {
            User::where('email', $request->email)->update([
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'email'           => $request->email,
                'password'        => Hash::make($request->password),
                'fcm_token'       => $request->fcm_token,
                'platform_type'   => $request->platform_type,
                'is_registration' => '1'
            ]);
            $user = User::where('email', $request->email)->first();
        } else {
            if ($request->user_account_id) {
                $useraccount = User::where('id', $request->user_account_id)->first();
                if ($useraccount && $useraccount->user_account_id != null) {
                    $user_account_id = $useraccount->user_account_id;
                } else {
                    $user_account_id = $useraccount->id;
                }
            } else {
                $user_account_id = null;
            }
            $user = User::create([
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'email'           => $request->email,
                'password'        => Hash::make($request->password),
                'fcm_token'       => $request->fcm_token,
                'platform_type'   => $request->platform_type,
                'user_account_id' => $user_account_id
            ]);
        }

        UserProfile::create([
            'user_id'       => $user->id,
            'profile_image' => 'https://picastroapp.s3.eu-west-2.amazonaws.com/profileImages/default-user.png'
        ]);

        if ($request->current_user_id) {
            User::where('id', $request->current_user_id)->update([
                'fcm_token' => null
            ]);
        }
        $user = User::with('userprofile')->where('id', $user->id)->first();

        $user_accounts = User::with('userprofile')->where('id', $user_account_id)->get();
        $token = $user->createToken('Picastro')->plainTextToken;
        $data = [
            'token' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            'user' => $user,
            'user_accounts' => $user_accounts ?? null
        ];
        return $this->success(['User Registered Successfully'], $data);
    }

    public function signupTest(Request $request)
    {
        $rules = [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email' => ['required', 'email', new ValidEmail, 'unique:users'],
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.required'             => 'Email Address is required.',
            'password.required'          => 'Password is required.',
            'confirm_password.required'  => 'confirm password is required.',
            'password.min'               => 'Password must be atleast 8 characters long.',

        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'fcm_token'     => $request->fcm_token,
            'platform_type' => $request->platform_type
        ]);

        UserProfile::create([
            'user_id'       => $user->id,
            'profile_image' => 'https://picastroapp.s3.eu-west-2.amazonaws.com/profileImages/default-user.png'
        ]);

        $user = User::with('userprofile')->where('id', $user->id)->first();
        $token = $user->createToken('Picastro')->plainTextToken;
        $data = [
            'token' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            'user' => $user,
        ];

        if ($user->status == 1) {
            return $this->success(['User Registered Successfully'], $data);
        }
    }
}
