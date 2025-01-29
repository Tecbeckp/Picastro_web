<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\ContactUsMail;
use App\Models\Coupons;
use App\Models\AppVersion;
use App\Models\BlockToUser;
use App\Models\BulkNotification;
use App\Models\Content;
use App\Models\GiveStar;
use App\Models\HidePost;
use App\Models\IsRegistration;
use App\Models\ObjectType;
use App\Models\Notification;
use App\Models\PostImage;
use App\Models\Report;
use App\Models\SaveObject;
use App\Models\PaymentMethodStatus;
use App\Models\Trophy;
use App\Models\User;
use App\Models\VoteImage;
use App\Models\ContactUs;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CommentWarning;
use App\Models\PostComment;
use App\Models\FollowerList;
use App\Models\Faq;
use App\Models\TrialPeriod;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\PusherHelper;
use App\Models\Setting;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\ChatImage;
use App\Models\GalleryImage;
use App\Models\GiftSubscription;
use App\Models\PreviousImageOfWeek;
use App\Models\SubscriptionHistory;
use App\Models\SubscriptionPlan;
use App\Models\UserProfile;
use App\Models\WeekOfTheImage;
use App\Models\RatingPopup;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ApiGeneralController extends Controller
{
    use ApiResponseTrait;
    use UploadImageTrait;
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function testNotification()
    {
        $targetToken = auth()->user()->fcm_token; // Adjust according to your data structure
        $notification = null;
        // Send the notification
        $this->notificationService->sendNotification(
            'TestNotification',
            'Testing Notification successfully.',
            $targetToken,
            json_encode($notification)
        );
    }
    public function follow(Request $request)
    {
        $follower = Auth::user();

        $user = User::where('id', $request->id)->whereNot('id', '1')->first();
        $user_notification_setting = NotificationSetting::where('user_id', $request->id)->first();
        if ($user) {
            if ($follower->id === $user->id) {
                return $this->error(['You cannot follow yourself']);
            }
            if (!$follower->followings()->where('user_id', $user->id)->exists()) {
                $follower->followings()->attach($user->id, ['created_at' => now(), 'updated_at' => now()]);

                $user->userprofile->increment('followers');
                $follower->userprofile->increment('following');

                $following = FollowerList::where('user_id', $follower->id)->where('follower_id', $user->id)->first();
                if ($following) {
                    $description = $follower->username . ' followed you back.';
                    $follower_id = null;
                } else {
                    $description = $follower->username . ' followed you. follow back?';
                    $follower_id = $follower->id;
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->type    = 'New Followers';
                $notification->notification = $description;
                $notification->follower_id  = $follower_id;
                $notification->save();

                if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->follow == true)) {
                    $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                    $targetToken = $user->fcm_token;
                    if ($targetToken) {
                        $this->notificationService->sendNotification(
                            'New Followers',
                            $description,
                            $targetToken,
                            json_encode($getnotification)
                        );
                    }
                }
                return $this->success(['Followed successfully.'], []);
            } elseif ($follower->followings()->where('user_id', $user->id)->exists()) {
                $follower->followings()->detach($user->id);

                $user->userprofile->decrement('followers');
                $follower->userprofile->decrement('following');

                return $this->success(['Unfollowed successfully.'], []);
            } else {
                return $this->error(['Not following']);
            }
        } else {
            return $this->error(['Please enter valid id']);
        }
    }
    public function saveObject(Request $request)
    {
        $rules = [
            'post_image_id'   => 'required_if:save_object_id,null|numeric|exists:post_images,id',
            'object_type_id'  => 'nullable|numeric',
            'save_object_id'  => 'nullable|numeric|exists:save_objects,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        if ($request->save_object_id) {
            $save_obj = SaveObject::where('id', $request->save_object_id)->first();
            if ($save_obj) {
                $save_obj->update([
                    'archived' => '1'
                ]);
                return $this->success(['Object archived  successfully!'], []);
            } else {
                return $this->error(['Invalid save object ID.']);
            }
        } else {
            $post = PostImage::where('id', $request->post_image_id)->first();

            $save_object                 =  new SaveObject();
            $save_object->user_id        = auth()->id();
            $save_object->object_type_id = $request->object_type_id ?? ($post ? $post->object_type_id : null);
            $save_object->post_image_id  = $request->post_image_id;
            $save_object->save();
            return $this->success(['Object saved  successfully!'], []);
        }
    }

    public function getSaveObject(Request $request)
    {
        $save_objects = ObjectType::get();
        $data = [];
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', null);
        $archived = $request->input('archived', '0');
        foreach ($save_objects as $obj) {

            $objects = SaveObject::with([
                'user',
                'postImage.StarCard.StarCardFilter',
                'postImage.ObjectType',
                'postImage.Bortle',
                'postImage.ObserverLocation',
                'postImage.ApproxLunarPhase',
                'postImage.Telescope',
                'postImage.giveStar',
                'postImage.Follow',
                'postImage.blockToUser',
                'postImage.UserToBlock',
            ])
                ->where('user_id', auth()->id())
                ->where('object_type_id', $obj->id)
                ->where('archived', $archived)
                ->whereHas('postImage', function ($query) {
                    $query->doesntHave('blockToUser')
                        ->doesntHave('UserToBlock');
                })
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->whereHas('postImage', function ($subQuery) use ($searchTerm) {
                        $subQuery->where(function ($nestedQuery) use ($searchTerm) {
                            $nestedQuery->where('post_image_title', 'like', "%{$searchTerm}%")
                                ->orWhere('description', 'like', "%{$searchTerm}%")
                                ->orWhere('catalogue_number', 'like', "%{$searchTerm}%")
                                ->orWhere('object_name', 'like', "%{$searchTerm}%");
                        })
                            ->orWhereHas('ObjectType', function ($typeQuery) use ($searchTerm) {
                                $typeQuery->where('name', 'like', "%{$searchTerm}%");
                            });
                    });
                })
                ->whereHas('postImage', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->get();
            // ->paginate($perPage);
            $trophies = Trophy::select('id', 'name', 'icon')->get();
            $objects->isNotEmpty() ?
                $data[$obj->name] = [
                    // 'current_page' => $objects->currentPage(),
                    'data' => $objects->map(function ($object) use ($trophies) {
                        return [
                            'save_object_id' => $object->id,
                            'post_image_details' => $object->postImage ? [
                                'id'                 => $object->postImage->id,
                                'user_id'            => $object->postImage->user_id,
                                'post_image_title'   => $object->postImage->post_image_title,
                                'image'              => $object->postImage->image,
                                'original_image'     => $object->postImage->original_image,
                                'description'        => $object->postImage->description,
                                'video_length'       => $object->postImage->video_length,
                                'number_of_frame'    => $object->postImage->number_of_frame,
                                'number_of_video'    => $object->postImage->number_of_video,
                                'exposure_time'      => $object->postImage->exposure_time,
                                'total_hours'        => $object->postImage->total_hours,
                                'additional_minutes' => $object->postImage->additional_minutes,
                                'catalogue_number'   => $object->postImage->catalogue_number,
                                'object_name'        => $object->postImage->object_name,
                                'ir_pass'            => $object->postImage->ir_pass,
                                'planet_name'        => $object->postImage->planet_name,
                                'location'           => $object->postImage->location,
                                'ObjectType'         => $object->postImage->ObjectType,
                                'Bortle'             => $object->postImage->Bortle,
                                'ObserverLocation'   => $object->postImage->ObserverLocation,
                                'ApproxLunarPhase'   => $object->postImage->ApproxLunarPhase,
                                'Telescope'          => $object->postImage->Telescope,
                                'giveStar'           => $object->postImage->giveStar ? true : false,
                                'Follow'             => $object->postImage->Follow ? true : false,
                                'trophy'             => $trophies,
                                'star_card'          => $object->postImage->StarCard,
                                'user'               => [
                                    'id'             => $object->postImage->user->id,
                                    'first_name'     => $object->postImage->user->first_name,
                                    'last_name'      => $object->postImage->user->last_name,
                                    'username'       => $object->postImage->user->username,
                                    'profile_image'  => $object->postImage->user->userprofile->profile_image,
                                    'fcm_token'      => $object->postImage->user->fcm_token,
                                ],
                            ] : null,
                        ];
                    }),
                ] : $data[$obj->name] = null;
        }

        return $this->success(['Get saved Object successfully!'], $data);
    }



    public function deleteSaveObject(Request $request)
    {
        $id = $request->id;
        $archived = $request->archived;
        if ($id) {
            $StarCamp =  SaveObject::find($id);
            if ($StarCamp) {
                if ($archived) {
                    $StarCamp->update([
                        'archived' => '0'
                    ]);
                    return $this->success(['Object unarchive  successfully!'], []);
                } else {
                    $StarCamp->delete();
                    return $this->success(['Saved object deleted successfully!'], []);
                }
            } else {
                return $this->error(['Please enter valid Saved object id']);
            }
        } else {
            return $this->error(['Saved object id is required']);
        }
    }

    public function givStar(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $postimage =  PostImage::find($id);
            if ($postimage) {
                $star = GiveStar::where('user_id', auth()->id())->where('post_image_id', $id)->first();
                if ($star) {
                    $star->delete();
                    return $this->success(['Star removed successfully!'], []);
                } else {
                    $givestar = new GiveStar();
                    $givestar->user_id       = auth()->id();
                    $givestar->post_user_id  = $postimage->user_id;
                    $givestar->post_image_id = $id;
                    $givestar->month         = date('m-Y');
                    $givestar->save();

                    $post = PostImage::with('user')->where('id', $id)->first();

                    if ($post->user_id != auth()->id()) {
                        $notification               = new Notification();
                        $notification->user_id      = $post->user_id;
                        $notification->type         = 'Stars';
                        $notification->post_image_id = $id;
                        $notification->notification = 'You received a star from ' . auth()->user()->username . '. Check it out.';
                        $notification->save();

                        $user_notification_setting = NotificationSetting::where('user_id', $post->user->id)->first();
                        if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->star == true)) {
                            $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                            if ($post->user && $post->user->fcm_token) {
                                $this->notificationService->sendNotification(
                                    'Stars',
                                    'You received a star from ' . auth()->user()->username . '. Check it out.',
                                    $post->user->fcm_token,
                                    json_encode($getnotification)
                                );
                            }
                        }
                    }

                    return $this->success(['Star added successfully!'], []);
                }
            } else {
                return $this->error(['Please enter valid post image id']);
            }
        } else {
            return $this->error(['Post image id is required']);
        }
    }

    public function imageOfMonth(Request $request)
    {
        $data = VoteImage::with([
            'postImage.user',
            'postImage.StarCard.StarCardFilter',
            'postImage.ObjectType',
            'postImage.Bortle',
            'postImage.ObserverLocation',
            'postImage.ApproxLunarPhase',
            'postImage.Telescope',
            'postImage.giveStar',
            'postImage.totalStar',
            'postImage.Follow',
            'postImage.votedTrophy'
        ])->whereHas('postImage', function ($q) {
            $q->whereNull('deleted_at');
        })->where('IOT', '1')
            ->latest()->get();
        if ($data->isNotEmpty()) {
            $data->transform(function ($post) {
                return [
                    'month'              => date('M Y', strtotime('01-' . $post->month)),
                    'object_type' => $post->postImage->ObjectType ? $post->postImage->ObjectType->name : 'Other',
                    'post_image' => [
                        'id'                 => $post->postImage->id,
                        'user_id'            => $post->postImage->user_id,
                        'post_image_title'   => $post->postImage->post_image_title,
                        'image'              => $post->postImage->image,
                        'original_image'     => $post->postImage->original_image,
                        'description'        => $post->postImage->description,
                        'video_length'       => $post->postImage->video_length,
                        'number_of_frame'    => $post->postImage->number_of_frame,
                        'number_of_video'    => $post->postImage->number_of_video,
                        'exposure_time'      => $post->postImage->exposure_time,
                        'total_hours'        => $post->postImage->total_hours,
                        'additional_minutes' => $post->postImage->additional_minutes,
                        'catalogue_number'   => $post->postImage->catalogue_number,
                        'object_name'        => $post->postImage->object_name,
                        'ir_pass'            => $post->postImage->ir_pass,
                        'planet_name'        => $post->postImage->planet_name,
                        'ObjectType'         => $post->postImage->ObjectType,
                        'Bortle'             => $post->postImage->Bortle,
                        'ObserverLocation'   => $post->postImage->ObserverLocation,
                        'ApproxLunarPhase'   => $post->postImage->ApproxLunarPhase,
                        'location'           => $post->postImage->location,
                        'Telescope'          => $post->postImage->Telescope,
                        'giveStar'           => $post->postImage->giveStar ? true : false,
                        'totalStar'          => $post->postImage->totalStar ? $post->postImage->totalStar->count() : 0,
                        'Follow'             => $post->postImage->Follow ? true : false,
                        'voted_trophy_id'    => $post->postImage->votedTrophy ? $post->postImage->votedTrophy->trophy_id : null,
                        'gold_trophy'        => $post->postImage->gold_trophy,
                        'silver_trophy'      => $post->postImage->silver_trophy,
                        'bronze_trophy'      => $post->postImage->bronze_trophy,
                        'star_card'          => $post->postImage->StarCard,
                        'user'               => [
                            'id'             => $post->postImage->user->id,
                            'first_name'     => $post->postImage->user->first_name,
                            'last_name'      => $post->postImage->user->last_name,
                            'username'       => $post->postImage->user->username,
                            'profile_image'  => $post->postImage->user->userprofile->profile_image,
                            'fcm_token'      => $post->postImage->user->fcm_token,
                        ]
                    ]
                ];
            });

            return $this->success(['Get Image of month successfully!'], $data);
        } else {
            return $this->error(['No data found']);
        }
    }
    public function voteImage(Request $request)
    {
        $rules = [
            'post_id'   => 'required|numeric',
            'trophy_id' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $post_id    = $request->post_id;
        $trophy_id  = $request->trophy_id;
        if ($post_id) {
            $postimage =  PostImage::find($post_id);
            if ($postimage) {
                $trophy = Trophy::where('id', $trophy_id)->first();
                $vote = VoteImage::where('post_image_id', $post_id)->where('user_id', auth()->id())->first();
                if ($vote) {
                    return $this->error(['You have already voted on this post.']);
                }
                if ($trophy) {
                    $voteimage                = new VoteImage();
                    $voteimage->user_id       = auth()->id();
                    $voteimage->post_user_id  = $postimage->user_id;
                    $voteimage->post_image_id = $post_id;
                    $voteimage->trophy_id     = $trophy_id;
                    $voteimage->month         = date('m-Y');
                    $voteimage->save();

                    $post = PostImage::with('user')->where('id', $post_id)->first();

                    if ($post->user_id != auth()->id()) {
                        $notification                = new Notification();
                        $notification->user_id       = $post->user_id;
                        $notification->type          = 'Trophies';
                        $notification->post_image_id = $post_id;
                        $notification->trophy_id     = $trophy_id;
                        $notification->notification  = 'Someone awarded you a trophy on your image. Check it out.';
                        $notification->save();

                        $user_notification_setting = NotificationSetting::where('user_id', $post->user->id)->first();
                        if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->trophy == true)) {
                            $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                            if ($post->user && $post->user->fcm_token) {
                                $this->notificationService->sendNotification(
                                    'Trophies',
                                    'Someone awarded you a trophy on your image. Check it out.',
                                    $post->user->fcm_token,
                                    json_encode($getnotification)
                                );
                            }
                        }
                    }
                    return $this->success(['Vote added successfully!'], []);
                } else {
                    return $this->error(['Please enter valid trophy id']);
                }
            } else {
                return $this->error(['Please enter valid post image id']);
            }
        } else {
            return $this->error(['Post image id is required']);
        }
    }
    public function report(Request $request)
    {
        $rules = [
            'post_id'   => 'required|numeric|exists:post_images,id',
            'reason'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $post_id = $request->post_id;
        $reason  = $request->reason;
        $post = PostImage::where('id', $post_id)->first();
        if ($post) {
            $report                = new Report();
            $report->user_id       = auth()->id();
            $report->post_image_id = $post_id;
            $report->post_user_id  = $post->user_id;
            $report->reason        = $reason;
            $report->save();

            $total_report = Report::where('post_user_id', $post->user_id)->groupBy('user_id')->count();
            if ($total_report >= '3') {
                User::where('id', $post->user_id)->update([
                    'status' => '0'
                ]);
            }
            return $this->success(['Report sent successfully!'], []);
        } else {
            return $this->error(['Please enter valid post image id']);
        }
    }

    public function blockToUser(Request $request)
    {
        $rules = [
            'block_user_id'    => 'required|numeric|exists:users,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        if($request->block == 'false'){
            $data = BlockToUser::where('user_id', auth()->id())->where('block_user_id', $request->block_user_id)->first();
        if ($data) {
            $data->delete();
            return $this->success(['User unblocked successfully!'], []);
        } elseif ($request->block_user_id == auth()->id()) {
            return $this->error(["You already unblock this user."]);
        } else {
            BlockTouser::where('user_id', auth()->id())->where('block_user_id', $request->block_user_id)->delete();
        }
        }else{
            $data = BlockToUser::where('user_id', auth()->id())->where('block_user_id', $request->block_user_id)->first();
            if ($data) {
                return $this->error(['You already blocked this user.']);
            } elseif ($request->block_user_id == auth()->id()) {
                return $this->error(["You can't block yourself."]);
            } else {
                $blockToUser = new BlockToUser();
                $blockToUser->user_id = auth()->id();
                $blockToUser->block_user_id = $request->block_user_id;
                $blockToUser->save();
    
                $follower =  FollowerList::where('user_id', $request->block_user_id)->where('follower_id', auth()->id())->first();
                if ($follower) {
                    $user = User::with('userprofile')->where('id', auth()->id())->whereNot('id', '1')->first();
                    $blockuser = User::with('userprofile')->where('id', $request->block_user_id)->whereNot('id', '1')->first();
    
                    $blockuser->userprofile->decrement('followers');
                    $user->userprofile->decrement('following');
                    $follower->delete();
                }
                $following =  FollowerList::where('follower_id', $request->block_user_id)->where('user_id', auth()->id())->first();
                if ($following) {
                    $blockuser = User::with('userprofile')->where('id', $request->block_user_id)->whereNot('id', '1')->first();
                    $user = User::with('userprofile')->where('id', auth()->id())->whereNot('id', '1')->first();
                    $blockuser->userprofile->decrement('following');
                    $user->userprofile->decrement('followers');
                    $following->delete();
                }
                return $this->success(['User blocked successfully!'], []);
            }
        }
        
    }

    public function getBlockedUser(Request $request){

        $data = BlockToUser::with('blockedUser.userprofile')->where('user_id', auth()->id());

        if ($request->search) {
            $search = $request->search;
            $data->whereHas('blockedUser', function ($q) use ($search) {
                $q->whereAny(['first_name', 'last_name', 'username'], 'LIKE', '%' . $search . '%');
            });
        }
        $data->whereHas('blockedUser', function ($q) {
            $q->whereNull('deleted_at');
        });
        $data = $data->paginate(100);

        $data->getCollection()->transform(function ($user) {
            $data = $user->blockedUser;
            return $data;
        });

        return $this->success(['Get blocked user list Successfully'], $data);

    }

    public function contactUs(Request $request)
    {
        $rules = [
            'username'   => 'required',
            'email'      => 'required|email',
            'message'    => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $contact           = new ContactUs();
        $contact->user_id  = auth()->id();
        $contact->username = $request->username;
        $contact->email    = $request->email;
        $contact->message  = $request->message;
        $contact->save();

        $details = [
            'name'     => $request->name,
            'email'    => $request->email,
            'message'  => $request->message
        ];
        $html = view('emails.contact-us', compact('details'))->render();
        EmailHelper::sendMail($request->email, 'New Contact Us Message', $html, null);

        return $this->success(['Sent successfully!'], []);
    }

    public function getContent(Request $request)
    {
        $contents = Content::all();
        $data = $contents->mapWithKeys(function ($content) {
            $links = null;
            if (!is_null($content->links)) {
                foreach (json_decode($content->links) as $link) {
                    $links[] = [
                        'icon' => asset($link->icon),
                        'link' => $link->link
                    ];
                }
            }
            return [
                $content->name => [
                    'id' => $content->id,
                    'links' => $links,
                    'content' => $content->content,
                ],
            ];
        });

        $faq = Faq::select('title', 'description')->where('status', 'Enable')->get() ?? null;
        $data['faq'] = $faq->isNotEmpty() ? $faq : null;
        $data['ios_version'] = AppVersion::latest()->first()->ios_version;
        $data['android_version'] = AppVersion::latest()->first()->android_version;
        $data['payment_methods'] = PaymentMethodStatus::first();
        $data['ios_screenshot'] = IsRegistration::first()->ios_screenshot;
        $data['android_screenshot'] = IsRegistration::first()->android_screenshot;
        $data['trial_period'] = TrialPeriod::first();
        $is_register = IsRegistration::latest()->first();
        $maintenance = Setting::latest()->first();
        if ($request->platform_type == 'android') {
            $data['app_under_maintenance'] = $maintenance ? $maintenance->maintenance_android : false;
        } elseif ($request->platform_type == 'iOS') {
            $data['app_under_maintenance'] = $maintenance ? $maintenance->maintenance_ios : false;
        } else {
            $data['app_under_maintenance'] = false;
        }
        $data['app_under_maintenance_for_only_android_version'] = $maintenance ? $maintenance->maintenance_android_version : '';
        $data['app_under_maintenance_for_only_ios_version']     = $maintenance ? $maintenance->maintenance_ios_version : '';
        $data['maintenance_title'] = $maintenance ? $maintenance->maintenance_title : '';
        $data['maintenance_description'] = $maintenance ? $maintenance->maintenance_description : '';
        if (isset($is_register) && $is_register->android == '0' && $request->platform_type == 'android') {
            $data['enable_plan'] = false;
        } elseif (isset($is_register) && $is_register->ios == '0' && $request->platform_type == 'iOS') {
            $data['enable_plan'] = false;
        } else {
            $data['enable_plan'] = true;
        }

        $data['comment_character_length'] = 400;
        $rating = RatingPopup::latest()->first();
        $data['rating_info_string'] = $rating->description ?? '';
        $used_trial = User::where('id', $request->user_id)->whereIn('trial_period_status', ['0', '2'])->first();
        $used_subscription = User::where('id', $request->user_id)->where('subscription_id', '4')->first();
        $subscription_plan = SubscriptionPlan::where('status', '1')->orderBy('place', 'asc')->get();
        $data['subscription_plan'] = $subscription_plan->map(function ($plan) use ($used_trial, $used_subscription) {
            if ($used_trial && $plan->id == 1) {
                $already_taken = true;
            } elseif ($used_subscription && $plan->id == $used_subscription->subscription_id) {
                $already_taken = true;
            } else {
                $already_taken = false;
            }
            return [
                'id' => $plan->id,
                'plan_name' => $plan->plan_name,
                'plan_price' => $plan->plan_price,
                'stripe_plan_id' => $plan->stripe_plan_id,
                'stripe_price_id' => $plan->stripe_price_id,
                'paypal_plan_id' => $plan->paypal_plan_id,
                'paypal_price_id' => $plan->paypal_price_id,
                'description' => $plan->description,
                'post_limit' => $plan->post_limit,
                'image_size_limit' => $plan->image_size_limit,
                'created_at' => $plan->created_at,
                'already_taken' =>  $already_taken
            ];
        });

        $user_trial = User::where('id', $request->user_id)->where('trial_period_status', '2')->first();
        if ($user_trial) {
            $current_time = date('d-m-Y H:i:s');
            $end_time = $user_trial->trial_ends_at;
            $current_timestamp = strtotime($current_time);
            $end_timestamp = strtotime($end_time);
            $remaining_time_in_seconds = $end_timestamp - $current_timestamp;
            $data['remaining_trail_time'] = "$remaining_time_in_seconds";
            $data['trail_end_time'] = date('d/m/y H:i', strtotime($user_trial->trial_ends_at));
        } else {
            $data['remaining_trail_time'] = null;
            $data['trail_end_time'] = null;
        }
        return $this->success([], $data);
    }

    public function warningComment(Request $request)
    {
        $rules = [
            'comment_id'   => 'required|numeric|exists:post_comments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $warning = CommentWarning::where('user_id', auth()->id())->where('comment_id', $request->comment_id)->first();

        if ($warning) {
            return $this->error(['You already report on this comment.']);
        } else {
            $comments             = new CommentWarning();
            $comments->user_id    = auth()->id();
            $comments->comment_id = $request->comment_id;
            $comments->save();
        }
        $warningCount = CommentWarning::where('comment_id', $request->comment_id)->count();
        if ($warningCount >= 5) {
            $post = PostComment::find($request->comment_id);
            if ($post) {
                $userPost = $post->first();
                $post->delete();
                $user = User::where('id', $userPost->user_id)->frist();
                $notification               = new Notification();
                $notification->user_id      = $user->id;
                $notification->type         = 'Comments';
                $notification->notification = 'Your comment has been deleted';
                $notification->save();
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                if ($user->fcm_token) {
                    $this->notificationService->sendNotification(
                        'Comments',
                        'Your comment has been deleted',
                        $user->fcm_token,
                        json_encode($getnotification)
                    );
                }
            }
        }
        $data = [
            'warning_count' => $warningCount
        ];
        return $this->success(['Comment reported successfully!'], $data);
    }

    public function getNotification()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', '0')->latest()->get();

        $total_notifications = Notification::where('user_id', auth()->id())
            ->where('is_open', '0')->latest()->get();

        $groupedNotifications = $notifications->groupBy(function ($item) {
            // Specify types to group explicitly, or else group them as "Others"
            $knownTypes = ['New Followers', 'Trophies', 'Stars', 'Leading Light Rewards', 'Comments', 'Image of the month', 'Posts']; // Adjust these as needed
            return in_array($item->type, $knownTypes) ? $item->type  :  'Others';
        })->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'title'             => $item->type,
                    'description'       => $item->notification,
                    'follower_id'       => $item->follower_id,
                    'post_image_id'     => $item->post_image_id,
                    'trophy_id'         => $item->trophy_id,
                    'comment_id'        => $item->comment_id,
                    'reply_comment_id'  => $item->reply_comment_id,
                    'bulk_notification'  => $item->bulk_notification,
                ];
            });
        });


        $responseData = $groupedNotifications->isEmpty() ? null : $groupedNotifications;
        $responseData['total_unread_notifications'] = $total_notifications->count();

        return $this->success(['Get notification successfully'], $responseData);
    }

    public function readNotification(Request $request)
    {
        $rules = [
            'notification_id'   => 'nullable|numeric|exists:notifications,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        if (isset($request->notification_id)) {
            Notification::where('user_id', auth()->id())->where('id', $request->notification_id)->update([
                'is_read' => '1'
            ]);
        } elseif (isset($request->type)) {
            Notification::where('user_id', auth()->id())->where('type', $request->type)->update([
                'is_read' => '1'
            ]);
        } else {
            Notification::where('user_id', auth()->id())->update([
                'is_read' => '1'
            ]);
        }
        return $this->success(['Notification read Successfully'], []);
    }

    public function readAllNotification(Request $request)
    {
        Notification::where('user_id', auth()->id())->update([
            'is_open' => '1'
        ]);

        return $this->success(['Notification read Successfully'], []);
    }

    public function inviteUser(Request $request)
    {
        $rules = [
            'email_1'   => 'required|email',
            'email_2'   => 'nullable|email',
            'email_3'   => 'nullable|email',
            'email_4'   => 'nullable|email',
            'email_5'   => 'nullable|email'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $client = new Client();
        try {
            $response = $client->post('https://picastro.co.uk/bulk-email', [
                'form_params' => [
                    'name'  => auth()->user()->username,
                    'email_1' => $request->email_1,
                    'email_2' => $request->email_2,
                    'email_3' => $request->email_3,
                    'email_4' => $request->email_4,
                    'email_5' => $request->email_5
                ]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($responseBody['error']['status'])) {
                $errorStatus = $responseBody['error']['status'];
            }
        }
        return $this->success(['Invite sent successfully'], []);
    }

    public function getUserById(Request $request)
    {
        $rules = [
            'user_id'   => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $data = User::select('id', 'first_name', 'last_name', 'username', 'fcm_token')->with('userprofile:user_id,profile_image,pronouns')->whereIn('id', explode(',', $request->user_id))->get();

        return $this->success(['Get user successfully'], $data);
    }

    public function generateSharePostLink(Request $request)
    {
        try {
            $post_id = $request->post_id;
            $user_id = $request->user_id;
            if ($post_id) {
                $share_post_link = route('post', base64_encode($post_id));
            } elseif ($user_id) {
                $share_post_link = route('profile', base64_encode($user_id));
            } else {
                $pass = $request->gallery_password;
                UserProfile::where('user_id', auth()->id())->update(['gallery_password' => $pass]);
                $share_post_link = route('gallery', base64_encode(auth()->id()));
            }
            return $this->success(['Request Proccessed Successfully'], $share_post_link);
        } catch (\Throwable $th) {
            return $this->error(['Internal Server Error']);
        }
    }

    public function getSharedPost(Request $request)
    {
        $rules = [
            'url'   => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $baseUrl = url('');
        $dee = str_replace($baseUrl, '', $request->url);
        // dd(explode('/', $dee));
        if (explode('/', $dee)[1] == 'post') {
            $postId = explode('/', $dee)[2];
            $post_id = base64_decode($postId);

            $result = PostImage::with('user', 'StarCard.StarCardFilter', 'ObjectType', 'Bortle', 'ObserverLocation', 'ApproxLunarPhase', 'Telescope', 'giveStar', 'totalStar', 'Follow', 'votedTrophy')->where('id', $post_id)->get();
            $trophies = Trophy::select('id', 'name', 'icon')->get();
            $result->transform(function ($post) use ($trophies) {
                return [
                    'id'                 => $post->id,
                    'user_id'            => $post->user_id,
                    'post_image_title'   => $post->post_image_title,
                    'image'              => $post->image,
                    'original_image'      => $post->original_image,
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
        } else {
            $userId = explode('/', $dee)[2];
            $user_id = base64_decode($userId);

            $user = User::with('userprofile', 'Following')->withCount('TotalStar')->where('id', $user_id)->first();
            $trophies = Trophy::select('id', 'name', 'icon')->get();
            $vote = [];
            foreach ($trophies as $trophy) {
                $vote[$trophy->id] = VoteImage::where('trophy_id', $trophy->id)
                    ->where('post_user_id', $user_id)
                    ->count();
            }


            $posts = PostImage::with('user', 'StarCard.StarCardFilter', 'ObjectType', 'Bortle', 'ObserverLocation', 'ApproxLunarPhase', 'Telescope', 'giveStar', 'totalStar', 'Follow', 'votedTrophy')->where('user_id', $user_id)->whereDoesntHave('userHidePost')->latest()->get();
            $troph = Trophy::select('id', 'name', 'icon')->get();
            $result = [
                'user' => $user,
                'follow' => $user->Following ? true : false,
                'posts' => $posts->count(),
                'trophies' => $trophies->map(function ($trophy) use ($vote) {
                    return [
                        'user_id' => $trophy->id,
                        'user_name' => $trophy->name,
                        'user_icon' => $trophy->icon,
                        'user_total_trophy' => $vote[$trophy->id] ?? 0
                    ];
                }),
                'user_post' => $posts->transform(function ($post) use ($troph) {
                    return [
                        'id'                 => $post->id,
                        'user_id'            => $post->user_id,
                        'post_image_title'   => $post->post_image_title,
                        'image'              => $post->image,
                        'original_image'     => $post->original_image,
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
                        'gold_trophy'        => $post->gold_trophy,
                        'silver_trophy'      => $post->silver_trophy,
                        'bronze_trophy'      => $post->bronze_trophy,
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
        }


        return $this->success([], $result);
    }

    public function sendChatNotifications(Request $request)
    {
        $rules = [
            'title'       => 'required',
            'description' => 'required',
            'data'        => 'required',
            'fcm_token'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $targetToken = $request->fcm_token;
        if ($targetToken) {
            $this->notificationService->sendNotification(
                $request->title,
                $request->description,
                $targetToken,
                null,
                $request->data
            );
        }
        return $this->success(['Sent message successfully'], []);
    }

    public function getPaymentMethodStatus()
    {
        $data = PaymentMethodStatus::first();

        return $this->success(['get payment method status'], $data);
    }

    public function getBulkNotification(Request $request)
    {
        if ($request->ajax()) {
            $data = BulkNotification::latest();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('ID', function ($row) use (&$rowid) {
                    $rowid++;
                    return $rowid;
                })
                ->addColumn('success', function ($row) {
                    return $row->success_user ? count(json_decode($row->success_user)) : 0;
                })->addColumn('failed', function ($row) {
                    return $row->faild_user ? count(json_decode($row->faild_user)) : 0;
                })->rawColumns(['success', 'failed'])
                ->make(true);
        } else {
            return view('admin.notification.index');
        }
    }
    public function createBulkNotification()
    {
        return view('admin.notification.create');
    }

    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'message' => 'required',
            'user_type' => 'required'
        ]);

        // if ($request->user_type == 'All') {
        //     $users = User::whereNot('id', '1')->get();
        // } else {
        //     $users = User::whereNot('id', '1')->where('subscription', $request->user_type)->get();
        // }
        $users = User::whereIn('id', ['43','38','58'])->get();

        if ($users) {
            $success_user = [];
            $faild_user   = [];
            foreach ($users as $user) {
                if ($user->fcm_token) {
                    $notification = new Notification();
                    $notification->user_id = $user->id;
                    $notification->type    = $request->title;
                    $notification->notification = $request->message;
                    $notification->bulk_notification = '1';
                    $notification->save();

                    $user_notification_setting = NotificationSetting::where('user_id', $user->id)->first();
                    if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->other == true)) {

                        $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'bulk_notification', 'is_read')->where('id', $notification->id)->first();

                        $this->notificationService->sendNotification(
                            $request->title,
                            $request->message,
                            $user->fcm_token,
                            json_encode($getnotification)
                        );
                    }
                    $success_user[] = $user->id;
                } else {
                    $faild_user[] = $user->id;
                }
            }

            BulkNotification::create([
                'title' => $request->title,
                'message' => $request->message,
                'success_user' => json_encode($success_user),
                'faild_user' => json_encode($faild_user)
            ]);



            return redirect()->back()->with('success', 'Send successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function startTrialPeriod(Request $request)
    {
        $data = TrialPeriod::first();
        if ($data) {
            $user = User::where('id', auth()->id())->first();
            if ($user->trial_period_status == '1' && !isset($request->plan_id)) {
                $timeNow = Carbon::now();
                if ($data->time_period == 'minute') {
                    $time = $timeNow->addMinutes($data->number);
                } elseif ($data->time_period == 'hour') {
                    $time = $timeNow->addHours($data->number);
                } elseif ($data->time_period == 'day') {
                    $time = $timeNow->addDays($data->number);
                } elseif ($data->time_period == 'week') {
                    $time = $timeNow->addWeeks($data->number);
                } elseif ($data->time_period == 'month') {
                    $time = $timeNow->addMonths($data->number);
                } elseif ($data->time_period == 'year') {
                    $time = $timeNow->addYears($data->number);
                }

                User::where('id', auth()->id())->update([
                    'trial_start_at' => date('Y-m-d H:i:s'),
                    'trial_ends_at'  => $time->format('Y-m-d H:i:s'),
                    'trial_period_status'  => '2',
                    'subscription'      => '0',
                    'subscription_id'   => '1'
                ]);

                return $this->success(['Trial period active successfully.'], []);
            } elseif (isset($request->plan_id) && $request->plan_id > 0) {
                $user = User::where('id', auth()->id())->first();
                if ($user && $user->trial_period_status == '2') {
                    $user->update([
                        'trial_period_status' => '0'
                    ]);
                }
                $user->update([
                    'subscription_id'   => "$request->plan_id",
                    'subscription'      => '1'
                ]);
                SubscriptionHistory::create([
                    'user_id' => auth()->id(),
                    'subscription_id' => $request->plan_id
                ]);
                return $this->success(['Subscription active successfully.'], []);
            } else {
                return $this->error(['Your trial period has ended.']);
            }
        } else {
            return $this->error(['Something went wrong.']);
        }
    }

    public function pusherCommonKeys()
    {

        $data = [
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'cluster' => env('PUSHER_APP_CLUSTER')
        ];

        return $this->success(['Successfully.'], $data);
    }

    public function pusherAuths(Request $request)
    {
        $postdata = file_get_contents("php://input");
        $parsedData = array();
        parse_str($postdata, $parsedData);


        $socket_id = $parsedData['socket_id'];
        $channel_name = $parsedData['channel_name'];


        $pusher = new PusherHelper();
        $auth = $pusher->pusherAuth($channel_name, $socket_id, auth()->user());
        $auth = json_decode($auth);
        return response()->json($auth);
    }

    public function trialPeriod()
    {

        $data = TrialPeriod::where('id', '1')->first();
        return view('admin.trial_period', compact('data'));
    }

    public function storeTrialPeriod(Request $request)
    {

        $request->validate([
            'time_number'   => 'required',
            'period'        => 'required',
            'reminder_time' => 'required'
        ]);

        TrialPeriod::where('id', 1)->update([
            'number'        => $request->time_number,
            'time_period'   => $request->period,
            'reminder_time' => $request->reminder_time,
        ]);

        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function ratingPopup(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('userprofile')
                ->whereHas('userprofile', function ($q) {
                    $q->where('rating', '1');
                })->latest()->get();
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
                ->addColumn('image', function ($row) {
                    if ($row->userProfile) {
                        return '<img src="' . $row->userProfile->profile_image . '" alt="" class="avatar-xs rounded-3 me-2 material-shadow" style="border-radius: 50% !important;object-fit: cover;object-position: top;">';
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('username', function ($row) {
                    return $row->username ?? 'N/A';
                })
                ->rawColumns(['image'])
                ->make(true);
        } else {
            $data = RatingPopup::latest()->first();
            return view('admin.rating-popup', compact('data'));
        }
    }

    public function updateRatingPopup(Request $request)
    {

        $request->validate([
            'description' => 'required'
        ]);

        RatingPopup::updateOrCreate(
            ['id' => 1],
            ['description' => $request->description]
        );
        return redirect()->back()->with('success', 'Updated successfully.');
    }
    public function getFollower(Request $request)
    {
        $rules = [
            'user_id'  => 'required|numeric|exists:users,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $followers = FollowerList::with('follower.userprofile', 'follower.Following')
            ->whereDoesntHave('blockToUser')
            ->whereDoesntHave('UserToBlock')
            ->whereDoesntHave('FollowBlockToUser')
            ->whereDoesntHave('FollowUserToBlock')
            ->where('user_id', auth()->id());
        if ($request->search) {
            $search = $request->search;
            $followers->whereHas('follower', function ($q) use ($search) {
                $q->whereAny(['first_name', 'last_name', 'username'], 'LIKE', '%' . $search . '%');
            });
        }
        $followers->whereHas('follower', function ($q) {
            $q->whereNull('deleted_at');
        });

        $followers = $followers->paginate(100);
        $followers->transform(function ($follower) {
            $data = $follower->follower;
            $data->follow_back = $follower->follower->Following ? true : false;
            return $data;
        });
        return $this->success(['Get Followers list Successfully'], $followers);
    }

    public function getFollowing(Request $request)
    {
        $rules = [
            'user_id'  => 'required|numeric|exists:users,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $followings = FollowerList::with('following.userprofile')
            ->whereDoesntHave('blockToUser')
            ->whereDoesntHave('UserToBlock')
            ->whereDoesntHave('FollowBlockToUser')
            ->whereDoesntHave('FollowUserToBlock')
            ->where('follower_id', auth()->id());
        if ($request->search) {
            $search = $request->search;
            $followings->whereHas('following', function ($q) use ($search) {
                $q->whereAny(['first_name', 'last_name', 'username'], 'LIKE', '%' . $search . '%');
            });
        }
        $followings->whereHas('following', function ($q) {
            $q->whereNull('deleted_at');
        });
        $followings = $followings->paginate(100);

        $followings->getCollection()->transform(function ($following) {
            $data = $following->following;
            $data->unfollow = false;
            return $data;
        });
        return $this->success(['Get Following list Successfully'], $followings);
    }

    public function getGivenStarUser(Request $request)
    {
        $rules = [
            'user_id'  => 'required|numeric|exists:users,id',
            'post_id'  => 'required|numeric|exists:post_images,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $data = GiveStar::with('GivenUser.userprofile')->where('post_image_id', $request->post_id)->where('post_user_id', $request->user_id);
        if ($request->search) {
            $search = $request->search;
            $data->whereHas('GivenUser', function ($q) use ($search) {
                $q->whereAny(['first_name', 'last_name', 'username'], 'LIKE', '%' . $search . '%');
            });
        }
        $data = $data->paginate(100);

        $data->getCollection()->transform(function ($result) {
            return $result->GivenUser;
        });
        return $this->success(['Get star given list Successfully'], $data);
    }

    public function AllOverSearch(Request $request)
    {

        $rules = [
            'type'    => 'required|numeric',
            'search'  => 'nullable|string'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        if ($request->type == '1') {
            if ($request->search) {
                $users = User::with('userprofile', 'Following')
                    ->whereDoesntHave('blockToUser')
                    ->whereDoesntHave('UserToBlock')
                    ->whereAny(['first_name', 'last_name', 'username'], 'LIKE', '%' . $request->search . '%')->withCount('TotalStar')->where('is_registration', '1')->whereNotNull('username')->whereNot('id', auth()->id())->latest()->paginate(100);
                $users->getCollection()->transform(function ($user) {
                    $data = $user;
                    $data->unfollow = $user->Following ? false : true;
                    return $data;
                });
            } else {
                $authUserId = auth()->id();
                $followers = FollowerList::where('user_id', $authUserId)->pluck('follower_id')->toArray();
                $following = FollowerList::where('follower_id', $authUserId)->pluck('user_id')->toArray();

                $relatedUserIds = array_unique(array_merge($followers, $following));

                $users = User::with('userprofile', 'Following')->whereIn('id', $relatedUserIds)->withCount('TotalStar')->where('is_registration', '1')->whereNot('id', auth()->id())->latest()->paginate(100);
                $users->getCollection()->transform(function ($user) {
                    $data = $user;
                    $data->unfollow = $user->Following ? false : true;
                    return $data;
                });
            }

            return $this->success([], $users);
        } else {
            $posts = PostImage::with('user', 'StarCard.StarCardFilter', 'ObjectType', 'Bortle', 'ObserverLocation', 'ApproxLunarPhase', 'Telescope', 'giveStar', 'totalStar', 'Follow', 'votedTrophy')
                ->whereNot('user_id', auth()->id())
                ->whereDoesntHave('userHidePost')
                ->whereDoesntHave('blockToUser')
                ->whereDoesntHave('UserToBlock')
                ->where(function ($query) use ($request) {
                    $query->where('post_image_title', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('catalogue_number', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('object_name', 'LIKE', '%' . $request->search . '%')
                        ->orwhereHas('StarCard', function ($q) use ($request) {
                            $q->where('camera_type', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('telescope_name', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('scope_type', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('mount_name', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('imaging_camera', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('guide_camera', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('guide_scope', 'LIKE', '%' . $request->search . '%')
                                ->orwhere('camera_lens', 'LIKE', '%' . $request->search . '%');
                        })->orwhereHas('ObserverLocation', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })->orwhereHas('ObjectType', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })->orwhereHas('Telescope', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        });
                });

            $posts = $posts->latest()->paginate(100);
            $trophies = Trophy::select('id', 'name', 'icon')->get();
            $posts->getCollection()->transform(function ($post) use ($trophies) {
                return [
                    'id'                 => $post->id,
                    'user_id'            => $post->user_id,
                    'post_image_title'   => $post->post_image_title,
                    'image'              => $post->image,
                    'original_image'     => $post->original_image,
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
                    'gold_trophy'        => $post->gold_trophy,
                    'silver_trophy'      => $post->silver_trophy,
                    'bronze_trophy'      => $post->bronze_trophy,
                    'trophy'             => $trophies,
                    'star_card'           => $post->StarCard,
                    'user'               => [
                        'id'             => $post->user ? $post->user->id : null,
                        'first_name'     => $post->user ? $post->user->first_name : null,
                        'last_name'      => $post->user ? $post->user->last_name : null,
                        'username'       => $post->user ? $post->user->username : null,
                        'profile_image'  => $post->user ? $post->user->userprofile->profile_image : null,
                        'fcm_token'      => $post->user ? $post->user->fcm_token : null,
                    ]
                ];
            });
            return $this->success([], $posts);
        }
    }

    public function applyCoupon(Request $request)
    {
        $rules = [
            'coupon_code'          => 'required',
            'subscription_plan_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $coupon = Coupons::where('code', $request->coupon_code)->where('status', 'enabled')->first();
        $subscription = SubscriptionPlan::where('id', $request->subscription_plan_id)->where('id', '!=', '1')->first();
        if ($subscription) {
            if ($coupon) {
                if ($coupon->expires_at >= now()->format('Y-m-d')) {
                    if ($coupon->type == 'percentage') {
                        $discount_price = ($coupon->discount / 100) * $subscription->plan_price;
                    } else {
                        $discount_price = $coupon->discount;
                    }
                    $data = [
                        'actual_price'   => number_format($subscription->plan_price, 2),
                        'discount_price' => number_format($discount_price, 2),
                        'updated_price'  => number_format($subscription->plan_price - $discount_price, 2)
                    ];
                    return $this->success(['Apply coupon successfully.'], $data);
                } else {
                    return $this->error(['This coupon is expire']);
                }
            } else {
                return $this->error(['Invalid coupon code.']);
            }
        } else {
            return $this->error(['Select valid subscription plan.']);
        }
    }

    public function generalSetting()
    {
        $data = [];
        $data['is_registration'] = IsRegistration::where('id', '1')->first();
        $data['app_under_maintenance'] = Setting::where('id', '1')->first();
        return view('admin.general_setting', compact('data'));
    }

    public function maintenance()
    {
        $data = Setting::latest()->first();
        return view('admin.maintenance', compact('data'));
    }
    public function updateMaintenance(Request $request)
    {
        Setting::updateOrCreate(
            ['id' => $request->id ?? 1],
            [
                'maintenance_title' => $request->title,
                'maintenance_description' => $request->description,
                'maintenance_ios_version' => $request->ios_version,
                'maintenance_android_version' => $request->android_version,
                'maintenance_ios' => $request->maintenance_ios ? '1' : '0',
                'maintenance_android' => $request->maintenance_andriod ? '1' : '0',

            ]
        );
        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function sendGift(Request $request)
    {
        $rules = [
            'email'     => 'required|email',
            'my_email'  => 'required|email'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $user = User::where('email', $request->email)->first();
        $active_user = User::where('email', $request->my_email)->first();

        if ($user && $user->is_registration == '0' && $user->subscription == '1') {
            return $this->error(['This user already has a gifted plan.']);
        } elseif ($user && $user->subscription == '1') {
            return $this->error(['This user already has an active plan.']);
        } elseif ($user && $user->subscription == '0' && ($user->is_registration == '1'  || $user->is_registration == '0')) {
            return $this->success(['successfully.'], $user);
        } else {
            $data = User::create([
                'first_name'        => $request->email,
                'last_name'         => $request->email,
                'email'             => $request->email,
                'password'          => Hash::make('987654321'),
                'gift_subscription' => $active_user ? $active_user->id : null,
                'is_registration'   => '0'
            ]);
            GiftSubscription::create([
                'email' => $request->my_email,
                'gifted_email' => $request->email
            ]);
            $html = view('emails.gift_mail')->render();
            EmailHelper::sendMail($request->email, "Surprise! You've Been Gifted a Subscription!", $html, null);

            return $this->success(['successfully.'], $data);
        }
    }

    public function HidePost(Request $request)
    {

        $rules = [
            'post_id'  => 'required|exists:post_images,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $post = HidePost::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id
        ]);
        return $this->success(['Hide successfully.'], null);
    }

    public function imageOfweek(Request $request)
    {

        $data = WeekOfTheImage::with([
            'postImage.user',
            'postImage.StarCard.StarCardFilter',
            'postImage.ObjectType',
            'postImage.Bortle',
            'postImage.ObserverLocation',
            'postImage.ApproxLunarPhase',
            'postImage.Telescope',
            'postImage.giveStar',
            'postImage.totalStar',
            'postImage.Follow',
            'postImage.votedTrophy',
            'postImage.totalGoldTrophies'
        ])->whereHas('postImage.user', function ($q) {
            $q->where('id', '!=', 43);
        })
            ->whereHas('postImage', function ($q) {
                $q->whereNull('deleted_at');
            })->get();

        if ($data->isNotEmpty()) {
            $groupedData = $data->transform(function ($post) {
                return [
                    'place'              => 'place_' . $post->place,
                    'id'                 => $post->postImage->id,
                    'user_id'            => $post->postImage->user_id,
                    'post_image_title'   => $post->postImage->post_image_title,
                    'image'              => $post->postImage->image,
                    'original_image'     => $post->postImage->original_image,
                    'description'        => $post->postImage->description,
                    'video_length'       => $post->postImage->video_length,
                    'number_of_frame'    => $post->postImage->number_of_frame,
                    'number_of_video'    => $post->postImage->number_of_video,
                    'exposure_time'      => $post->postImage->exposure_time,
                    'total_hours'        => $post->postImage->total_hours,
                    'additional_minutes' => $post->postImage->additional_minutes,
                    'catalogue_number'   => $post->postImage->catalogue_number,
                    'object_name'        => $post->postImage->object_name,
                    'ir_pass'            => $post->postImage->ir_pass,
                    'planet_name'        => $post->postImage->planet_name,
                    'ObjectType'         => $post->postImage->ObjectType,
                    'Bortle'             => $post->postImage->Bortle,
                    'ObserverLocation'   => $post->postImage->ObserverLocation,
                    'ApproxLunarPhase'   => $post->postImage->ApproxLunarPhase,
                    'location'           => $post->postImage->location,
                    'Telescope'          => $post->postImage->Telescope,
                    'giveStar'           => $post->postImage->giveStar ? true : false,
                    'totalStar'          => $post->postImage->totalStar ? $post->postImage->totalStar->count() : 0,
                    'Follow'             => $post->postImage->Follow ? true : false,
                    'voted_trophy_id'    => $post->postImage->votedTrophy ? $post->postImage->votedTrophy->trophy_id : null,
                    'total_gold_trophies'    => $post->postImage->totalGoldTrophies ? $post->postImage->totalGoldTrophies->count() : 0,
                    'star_card'          => $post->postImage->StarCard,
                    'user'               => [
                        'id'             => $post->postImage->user->id,
                        'first_name'     => $post->postImage->user->first_name,
                        'last_name'      => $post->postImage->user->last_name,
                        'username'       => $post->postImage->user->username,
                        'profile_image'  => $post->postImage->user->userprofile->profile_image,
                        'fcm_token'      => $post->postImage->user->fcm_token,
                    ]
                ];
            })->groupBy('place');

            return $this->success(['Get Image of week successfully!'], $groupedData);
        } else {
            return $this->error(['No data found']);
        }
    }
    public function UpdateNotificationSetting(Request $request)
    {
        $rules = [
            'follow'        => 'required|in:0,1',
            'post'          => 'required|in:0,1',
            'trophy'        => 'required|in:0,1',
            'star'          => 'required|in:0,1',
            'comment'       => 'required|in:0,1',
            'comment_reply' => 'required|in:0,1',
            'like_comment'  => 'required|in:0,1',
            'other'         => 'required|in:0,1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $data = NotificationSetting::updateOrCreate(
            [
                'user_id' => auth()->id()
            ],
            [
                'follow'        => $request->follow,
                'post'          => $request->post,
                'trophy'        => $request->trophy,
                'star'          => $request->star,
                'comment'       => $request->comment,
                'comment_reply' => $request->comment_reply,
                'like_comment'  => $request->like_comment,
                'other'         => $request->other
            ]
        );

        if ($data) {
            return $this->success(['Updated successfully.'], $data);
        } else {
            return $this->error(['Something went wrong please try again.']);
        }
    }

    public function UploadChatImage(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpg,jpeg,png,webp,tif|max:122880'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $thumbnail         = $this->imageUpload($request->file('image'), 'assets/uploads/chatImageThumbnail/');
        $originalImageName = $this->originalImageUpload($request->file('image'), 'assets/uploads/chatImage/', true);

        $data = ChatImage::create([
            'original_image' => $originalImageName,
            'thumbnail'      => $thumbnail
        ]);

        return $this->success(['Uploaded successfully!'], $data);
    }

    public function getChatImage(Request $request)
    {
        $rules = [
            'image' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $baseUrl = "https://picastro.co.uk/public/";
        $image = str_replace($baseUrl, '', $request->image);
        $data = ChatImage::where('thumbnail', $image)->first();
        return $this->success(['Get chat image successfully!'], $data);
    }

    public function saveGalleryImage(Request $request)
    {

        $rules = [
            'id' => 'required|numeric|exists:post_images,id'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        if (isset($request->add) && $request->add == true) {
            $total =  GalleryImage::where('user_id', auth()->id())->count();
            $user = User::where('id', auth()->id())->first();
            if ($user->subscription_id == '4') {
                $limit = 5;
            } else {
                $limit = 15;
            }
            if (($user->subscription_id == '4' && $total < $limit) || ($user->subscription_id != '4' && $total < $limit)) {
                GalleryImage::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'post_image_id' => $request->id
                    ],
                    [
                        'user_id' => auth()->id(),
                        'post_image_id' => $request->id
                    ]
                );
                return $this->success(['Saved successfully!'], null);
            } else {
                return $this->error(['You can only save up to ' . $limit . ' images.']);
            }
        } elseif (isset($request->remove) && $request->remove == true) {
            $data =  GalleryImage::where('user_id', auth()->id())->where('post_image_id', $request->id)->first();
            if ($data) {
                $data->delete();
                return $this->success(['Removed successfully!'], null);
            } else {
                return $this->error(['Something went wrong.']);
            }
        } else {
            return $this->error(['Something went wrong.']);
        }
    }

    public function getGalleryImage()
    {
        $posts = GalleryImage::with('postImage')
            ->whereHas('postImage', function ($q) {
                $q->whereNull('deleted_at');
            })->where('user_id', auth()->id())->latest()->get();
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedPosts = new LengthAwarePaginator(
            $posts->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $posts->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginatedPosts->transform(function ($post) {
            return [
                'id'                 => $post->postImage->id,
                'user_id'            => $post->postImage->user_id,
                'post_image_title'   => $post->postImage->post_image_title,
                'image'              => $post->postImage->image,
                'original_image'     => $post->postImage->original_image,
                'description'        => $post->postImage->description,
                'video_length'       => $post->postImage->video_length,
                'number_of_frame'    => $post->postImage->number_of_frame,
                'number_of_video'    => $post->postImage->number_of_video,
                'exposure_time'      => $post->postImage->exposure_time,
                'total_hours'        => $post->postImage->total_hours,
                'additional_minutes' => $post->postImage->additional_minutes,
                'catalogue_number'   => $post->postImage->catalogue_number,
                'object_name'        => $post->postImage->object_name,
                'ir_pass'            => $post->postImage->ir_pass,
                'planet_name'        => $post->postImage->planet_name,
                'ObjectType'         => $post->postImage->ObjectType,
                'Bortle'             => $post->postImage->Bortle,
                'ObserverLocation'   => $post->postImage->ObserverLocation,
                'ApproxLunarPhase'   => $post->postImage->ApproxLunarPhase,
                'location'           => $post->postImage->location,
                'Telescope'          => $post->postImage->Telescope,
                'only_image_and_description' => $post->postImage->only_image_and_description,
                'star_card'          => $post->postImage->StarCard,
                'user'               => [
                    'id'             => $post->postImage->user ? $post->postImage->user->id : null,
                    'first_name'     => $post->postImage->user ? $post->postImage->user->first_name : null,
                    'last_name'      => $post->postImage->user ? $post->postImage->user->last_name : null,
                    'username'       => $post->postImage->user ? $post->postImage->user->username : null,
                    'profile_image'  => $post->postImage->user && $post->postImage->user->userprofile ? $post->postImage->user->userprofile->profile_image : null,
                    'fcm_token'      => $post->postImage->user ? $post->postImage->user->fcm_token : null,
                ]
            ];
        });

        return $this->success(['Get successfully.'], $paginatedPosts);
    }

    public function storeRating(Request $request)
    {
        $rules = [
            'rating' => 'required|numeric|in:1,2',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $rating = UserProfile::where('user_id', auth()->id())->update(
            [
                'rating' => $request->rating
            ]
        );
        return $this->success(['Rating successfully.'], $rating);
    }
    public function getHallOfFame(Request $request)
    {
        $year = $request->year;
        $monthly_data = [];
        for ($month = 1; $month <= 12; $month++) {
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

            $monthly_images = VoteImage::with([
                'postImage.user',
                'postImage.StarCard.StarCardFilter',
                'postImage.ObjectType',
                'postImage.Bortle',
                'postImage.ObserverLocation',
                'postImage.ApproxLunarPhase',
                'postImage.Telescope',
                'postImage.giveStar',
                'postImage.totalStar',
                'postImage.Follow',
                'postImage.votedTrophy'
            ])
                ->whereHas('postImage', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('IOT', '1')
                ->where('month', 'like', '%' . $formattedMonth . '-' . $year)
                ->latest()
                ->first();

            $image_of_week = PreviousImageOfWeek::with([
                'postImage.user',
                'postImage.StarCard.StarCardFilter',
                'postImage.ObjectType',
                'postImage.Bortle',
                'postImage.ObserverLocation',
                'postImage.ApproxLunarPhase',
                'postImage.Telescope',
                'postImage.giveStar',
                'postImage.totalStar',
                'postImage.Follow',
                'postImage.votedTrophy',
                'postImage.totalGoldTrophies'
            ])->where('place', 1)
                ->whereHas('postImage.user', function ($q) {
                    $q->where('id', '!=', 43);
                })
                ->whereHas('postImage', function ($q) {
                    $q->whereNull('deleted_at');
                })->where('week', 'like', '%' . $year . '-' . $formattedMonth . '%')->get();

            if ($monthly_images || $image_of_week->isNotEmpty()) {
                $monthly_data[] = [
                    'month' => date('M, Y', strtotime("{$year}-{$formattedMonth}-01")),
                    'image_of_the_month' => $this->transformPostData($monthly_images),
                    'image_of_the_week' => $image_of_week->transform(function ($post) {
                        return $this->transformPostData($post);
                    }),
                ];
            }
        }
        $monthly_data = array_reverse($monthly_data);
        return $this->success(['Get hall of fame successfully!'], $monthly_data);
    }
    private function transformPostData($post)
    {
        if (empty($post)) {
            return null;
        }
        return [
            'id'                 => $post->postImage->id,
            'user_id'            => $post->postImage->user_id,
            'post_image_title'   => $post->postImage->post_image_title,
            'image'              => $post->postImage->image,
            'original_image'     => $post->postImage->original_image,
            'description'        => $post->postImage->description,
            'video_length'       => $post->postImage->video_length,
            'number_of_frame'    => $post->postImage->number_of_frame,
            'number_of_video'    => $post->postImage->number_of_video,
            'exposure_time'      => $post->postImage->exposure_time,
            'total_hours'        => $post->postImage->total_hours,
            'additional_minutes' => $post->postImage->additional_minutes,
            'catalogue_number'   => $post->postImage->catalogue_number,
            'object_name'        => $post->postImage->object_name,
            'ir_pass'            => $post->postImage->ir_pass,
            'planet_name'        => $post->postImage->planet_name,
            'ObjectType'         => $post->postImage->ObjectType,
            'Bortle'             => $post->postImage->Bortle,
            'ObserverLocation'   => $post->postImage->ObserverLocation,
            'ApproxLunarPhase'   => $post->postImage->ApproxLunarPhase,
            'location'           => $post->postImage->location,
            'Telescope'          => $post->postImage->Telescope,
            'giveStar'           => $post->postImage->giveStar ? true : false,
            'totalStar'          => $post->postImage->totalStar ? $post->postImage->totalStar->count() : 0,
            'Follow'             => $post->postImage->Follow ? true : false,
            'voted_trophy_id'    => $post->postImage->votedTrophy->trophy_id ?? null,
            'gold_trophy'        => $post->postImage->gold_trophy,
            'silver_trophy'      => $post->postImage->silver_trophy,
            'bronze_trophy'      => $post->postImage->bronze_trophy,
            'star_card'          => $post->postImage->StarCard,
            'user'               => [
                'id'             => $post->postImage->user->id,
                'first_name'     => $post->postImage->user->first_name,
                'last_name'      => $post->postImage->user->last_name,
                'username'       => $post->postImage->user->username,
                'profile_image'  => $post->postImage->user->userprofile->profile_image,
                'fcm_token'      => $post->postImage->user->fcm_token,
            ]
        ];
    }
}
