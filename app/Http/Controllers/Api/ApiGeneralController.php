<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApproxLunarPhase;
use App\Models\AppVersion;
use App\Models\BlockToUser;
use App\Models\Bortle;
use App\Models\Content;
use App\Models\GiveStar;
use App\Models\ObjectType;
use App\Models\Notification;
use App\Models\PostImage;
use App\Models\Report;
use App\Models\SaveObject;
use App\Models\Trophy;
use App\Models\User;
use App\Models\VoteImage;
use App\Models\ContactUs;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CommentWarning;
use App\Models\PostComment;
use App\Models\FollowerList;
use App\Models\Faq;
use GuzzleHttp\Client;


class ApiGeneralController extends Controller
{
    use ApiResponseTrait;
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function testNotification()
    {
        $targetToken = auth()->user()->fcm_token; // Adjust according to your data structure

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
                    $description = $follower->username . ' just followed you back.';
                    $follower_id = null;
                } else {
                    $description = $follower->username . ' just followed you. follow back?';
                    $follower_id = $follower->id;
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->type    = 'New Followers';
                $notification->notification = $description;
                $notification->follower_id  = $follower_id;
                $notification->save();

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
            'post_image_id'   => 'required|numeric|exists:post_images,id',
            'object_type_id'  => 'nullable|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $post = PostImage::where('id', $request->post_image_id)->first();

        $save_object                 =  new SaveObject();
        $save_object->user_id        = auth()->id();
        $save_object->object_type_id = $request->object_type_id ?? ($post ? $post->object_type_id : null);
        $save_object->post_image_id  = $request->post_image_id;
        $save_object->save();

        return $this->success(['Object saved  successfully!'], []);
    }

    public function getSaveObject(Request $request)
    {
        $save_objects = ObjectType::get();
        $data = [];
        $perPage = $request->input('per_page', 10);

        foreach ($save_objects as $obj) {

            $objects = SaveObject::with('user', 'postImage.StarCard.StarCardFilter', 'postImage.ObjectType', 'postImage.Bortle', 'postImage.ObserverLocation', 'postImage.ApproxLunarPhase', 'postImage.Telescope', 'postImage.giveStar', 'postImage.Follow')
                ->where('user_id', auth()->id())
                ->where('object_type_id', $obj->id)->get();
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
                    // 'first_page_url' => $objects->url(1),
                    // 'from' => $objects->firstItem(),
                    // 'last_page' => $objects->lastPage(),
                    // 'last_page_url' => $objects->url($objects->lastPage()),
                    // 'links' => [
                    //     [
                    //         'url' => $objects->previousPageUrl(),
                    //         'label' => '&laquo; Previous',
                    //         'active' => $objects->onFirstPage()
                    //     ],
                    //     [
                    //         'url' => $objects->url($objects->currentPage()),
                    //         'label' => $objects->currentPage(),
                    //         'active' => true
                    //     ],
                    //     [
                    //         'url' => $objects->nextPageUrl(),
                    //         'label' => 'Next &raquo;',
                    //         'active' => !$objects->hasMorePages()
                    //     ]
                    // ],
                    // 'next_page_url' => $objects->nextPageUrl(),
                    // 'path' => $objects->path(),
                    // 'per_page' => $objects->perPage(),
                    // 'prev_page_url' => $objects->previousPageUrl(),
                    // 'to' => $objects->lastItem(),
                    // 'total' => $objects->total()
                ] : $data[$obj->name] = null;
        }

        return $this->success(['Get saved Object successfully!'], $data);
    }



    public function deleteSaveObject(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $StarCamp =  SaveObject::find($id);
            if ($StarCamp) {
                $StarCamp->delete();
                return $this->success(['Saved object deleted successfully!'], []);
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
                        $notification->notification = 'You just received a star from ' . auth()->user()->username . '. Check it out.';
                        $notification->save();

                        $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                        if ($post->user && $post->user->fcm_token) {
                            $this->notificationService->sendNotification(
                                'Stars',
                                'You just received a star from ' . auth()->user()->username . '. Check it out.',
                                $post->user->fcm_token,
                                json_encode($getnotification)
                            );
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
        ])
            ->select('post_image_id', 'post_user_id', 'month', DB::raw('count(id) as post_count'))
            ->whereHas('postImage', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->groupBy('post_image_id', 'post_user_id', 'month')
            ->orderBy('post_count', 'desc')
            ->get();
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
            return $this->error(['No data found for the given month']);
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
                        $notification->notification  = auth()->user()->username . ' just awarded you a trophy on your image. Check it out.';
                        $notification->save();

                        $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                        if ($post->user && $post->user->fcm_token) {
                            $this->notificationService->sendNotification(
                                'Trophies',
                                auth()->user()->username . ' just awarded you a trophy on your image. Check it out.',
                                $post->user->fcm_token,
                                json_encode($getnotification)
                            );
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

            return $this->success(['User blocked successfully!'], []);
        }
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

        return $this->success(['Sent successfully!'], []);
    }

    public function getContent()
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
        $data['ios_version'] = AppVersion::where('id', '1')->first()->ios_version;
        $data['android_version'] = AppVersion::where('id', '1')->first()->android_version;
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

        $notifications = Notification::where('user_id', auth()->id())->where('is_read', '0')->latest()->get();
        // dd($notifications->count());
        $groupedNotifications = $notifications->groupBy('type')->map(function ($items) {
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

                ];
            });
        });

        $responseData = $groupedNotifications->isEmpty() ? null : $groupedNotifications;
        $responseData['total_unread_notifications'] = $notifications->count();

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
        } else {
            Notification::where('user_id', auth()->id())->update([
                'is_read' => '1'
            ]);
        }
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
        try{
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
            dd($responseBody);
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
        $data = User::select('id', 'first_name', 'last_name', 'username', 'fcm_token')->with('userprofile:user_id,profile_image')->whereIn('id', explode(',', $request->user_id))->get();

        return $this->success(['Get user successfully'], $data);
    }

    public function generateSharePostLink(Request $request)
    {
        try {
            $post_id = $request->post_id;
            $share_post_link = route('post', base64_encode($post_id));
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
        if (explode('/', $request->url) && isset(explode('/', $request->url)[4])) {
            $id = explode('/', $request->url)[4];
        } else {
            $id = null;
        }

        $post_id = base64_decode($id);

        $posts = PostImage::with('user', 'StarCard.StarCardFilter', 'ObjectType', 'Bortle', 'ObserverLocation', 'ApproxLunarPhase', 'Telescope', 'giveStar', 'totalStar', 'Follow', 'votedTrophy')->where('id', $post_id)->get();
        $trophies = Trophy::select('id', 'name', 'icon')->get();
        $posts->transform(function ($post) use ($trophies) {
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
}
