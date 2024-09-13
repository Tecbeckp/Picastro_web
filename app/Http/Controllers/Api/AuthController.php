<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Otp;
use App\Models\Trophy;
use App\Models\User;
use App\Models\VoteImage;
use App\Traits\ApiResponseTrait;
use App\Traits\MailTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;
    use MailTrait;
    public function login(Request $request)
    {
        $rules = [
            'email'             => 'required|email',
            'password'          => 'required|min:8'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.required'     => 'Email Address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be atleast 8 characters long.'

        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $auth = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if ($auth) {
            if ($request->fcm_token) {
                user::where('id', Auth::id())->update([
                    'fcm_token'     => $request->fcm_token
                ]);
            }

            $user = User::with('userprofile')->withCount('TotalStar')->where('id', Auth::id())->first();
            $token = $user->createToken('Picastro')->plainTextToken;
            $trophies = Trophy::select('id', 'name', 'icon')->get();
            $vote = [];

            foreach ($trophies as $trophy) {
                $vote[$trophy->id] = VoteImage::where('trophy_id', $trophy->id)
                    ->where('post_user_id', auth()->id())
                    ->count();
            }
            $data = [
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ],
                'user' => $user,
                'trophies' => $trophies->map(function ($trophy) use ($vote) {
                    return [
                        'id' => $trophy->id,
                        'name' => $trophy->name,
                        'icon' => $trophy->icon,
                        'total_trophy' => $vote[$trophy->id] ?? 0
                    ];
                })
            ];

            if ($user->status == 1) {
                return $this->success(['Login Successfully'], $data);
            } else {
                Auth::logout();
                return $this->error(['We regret to inform you that your account privileges have been terminated for violating the terms and conditions.']);
            }
        } else {
            return $this->error(['Invalid Credentials']);
        }
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email'             => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'email.required'  => 'Email Address is required.',
            'email.email'     => 'Please enter a valid email address.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $user = User::where('email',$request->email)->first();
        // if($user){
            $otp = rand(1000,9999);
            Otp::updateOrCreate(
                [
                    'email' => $request->email
                ],
                [
                    'otp'   => $otp
                ]
            );
            $details = [
                'otp'   => $otp,
                'name'  => $user ? $user->username : null,
                'email' => $request->email
            ];
            
            $html = view('emails.forgot-password', [$details])->render();
            $data['from'] = 'support@picastroapp.com';
            $data['to'] = $request->email;
            $data['subject'] = 'Forgot Password';
            $data['html'] = $html;
            $this->sendMail($data);

            return $this->success(['OTP Send Successfully on your email address.'],1234);

        // }else{
        //     return $this->error(['The provided email does not match our records. Please check your email address and try again.']);
        // }

    }

    function VerifyOTP(Request $request)
    {
        $rules = [
            'email'           => 'required|email',
            'otp'             => 'required|max:4'
        ];
        $validator = Validator::make($request->all(), $rules, [
            'otp.required'    => 'OTP is required.',
            'email.required'  => 'Email Address is required.',
            'email.email'     => 'Please enter a valid email address.'
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $otp = Otp::where('email', $request->email)->latest()->first();
        if ($otp) {
            $expirationTime = $otp->updated_at->addSeconds(5 * 60);
            $currentDateTime = Carbon::now();
            if ($currentDateTime->isAfter($expirationTime)) {
                return $this->error(['OTP has been expired']);
            } elseif (1234 == $request->otp) {
                return $this->success(['OTP Verified Successfully'], []);
            } else {
                return $this->error(['The OTP you entered does not match. Please try again.']);
            }
        } else {
            return $this->error(['The provided email does not match our records. Please check your email address and try again.']);
        }
    }

    public function ResetPassword(Request $request)
    {
        $rules = [
            'email'             => 'required|email',
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'email.required'             => 'Email Address is required.',
            'email.email'                => 'Please enter a valid email address.',
            'password.required'          => 'Password is required.',
            'confirm_password.required'  => 'confirm password is required.',
            'password.min'               => 'Password must be atleast 8 characters long.'

        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return $this->success(['Password changed Successfully'], []);
        } else {
            return $this->error(['The provided email does not match our records. Please check your email address and try again.']);
        }
    }

    public function logout(Request $request)
    {

        if (Auth::check()) {
            if ($request->user()->fcm_token) {
                user::where('id', Auth::id())->update([
                    'fcm_token'     => null
                ]);
            }
            $request->user()->currentAccessToken()->delete();
            return $this->success(['Successfully logged out'], []);
        } else {
            return $this->error(['Already logout']);
        }
    }
}
