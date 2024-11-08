<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LikePostComment;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;


class PostCommentController extends Controller
{
    use ApiResponseTrait;
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getPostComment(Request $request)
    {
        $rules = [
            'post_id'   => 'required|numeric|exists:post_images,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $post_id = $request->post_id;

        $post_comments = PostComment::with('user.userprofile', 'ReplyComment.user.userprofile', 'ReplyComment.LikedComment')->where('post_image_id', $post_id)->whereNull('post_comment_id')->paginate(10);

        $post_comments->getCollection()->transform(function ($comment) {

            $comment->is_like = $comment->LikedComment ? true : false;

            foreach ($comment->ReplyComment as $reply) {
                $reply->is_like = $reply->LikedComment ? true : false;
            }

            return $comment;
        });
        return $this->success([], $post_comments);
    }

    public function getCommentById(Request $request)
    {
        $rules = [
            'comment_id'   => 'required|numeric|exists:post_comments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        $comment_id = $request->comment_id;

        $post_comments = PostComment::with('user.userprofile', 'ReplyComment.user.userprofile', 'ReplyComment.LikedComment')
            ->where('id', $comment_id)
            ->whereNull('post_comment_id')
            ->first();

        if ($post_comments) {
            // Check if the main comment is liked
            $post_comments->is_like = $post_comments->LikedComment ? true : false;

            // Check if any reply comments are liked
            $post_comments->ReplyComment->transform(function ($reply) {
                $reply->is_like = $reply->LikedComment ? true : false;
                return $reply;
            });
        }
        return $this->success([], $post_comments);
    }
    public function postComment(Request $request)
    {
        $rules = [
            'post_image_id'   => 'required|numeric|exists:post_images,id',
            'comment'         => 'required',
            'post_comment_id' => 'nullable|numeric|exists:post_comments,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $reply = PostComment::where('id', $request->post_comment_id)->first();
        if ($reply &&  $reply->post_image_id != $request->post_image_id) {
            return $this->error(["You cannot reply to a comment that is associated with a different post."]);
        }
        $comment                  = new PostComment();
        $comment->user_id         = auth()->id();
        $comment->post_image_id   = $request->post_image_id;
        $comment->post_comment_id = $request->post_comment_id;
        $comment->comment         = $request->comment;
        $comment->save();

        $post = PostImage::with('user')->where('id', $request->post_image_id)->first();
        $post_comment = PostComment::with('user')->where('id', $request->post_comment_id)->first();

        if ($request->post_comment_id && ($post_comment && $post_comment->user_id != auth()->id())) {

            $notification                    = new Notification();
            $notification->user_id           = $post_comment->user_id;
            $notification->type              = 'Comments';
            $notification->post_image_id     = $request->post_image_id;
            $notification->comment_id        = $request->post_comment_id;
            $notification->reply_comment_id  = $comment->id;
            $notification->notification = auth()->user()->username . ' just replied to your comment.';
            $notification->save();

            $user_notification_setting = NotificationSetting::where('user_id', $post_comment->user->id)->first();
            if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->comment_reply == true)) {
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'comment_id', 'reply_comment_id', 'is_read')->where('id', $notification->id)->first();
                if ($post_comment->user && $post_comment->user->fcm_token) {
                    $this->notificationService->sendNotification(
                        'Comments',
                        auth()->user()->username . ' just replied to your comment.',
                        $post_comment->user->fcm_token,
                        json_encode($getnotification)
                    );
                }
            }
        } elseif ($post->user_id != auth()->id()) {
            $notification                  = new Notification();
            $notification->user_id         = $post->user_id;
            $notification->type            = 'Comments';
            $notification->post_image_id   = $request->post_image_id;
            $notification->comment_id      = $comment->id;
            $notification->notification = auth()->user()->username . ' just commented on your post.';
            $notification->save();


            $user_notification_setting = NotificationSetting::where('user_id', $post->user->id)->first();
            if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->comment == true)) {
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'comment_id', 'reply_comment_id', 'is_read')->where('id', $notification->id)->first();
                if ($post->user && $post->user->fcm_token) {
                    $this->notificationService->sendNotification(
                        'Comments',
                        auth()->user()->username . ' just commented on your post.',
                        $post->user->fcm_token,
                        json_encode($getnotification)
                    );
                }
            }
        }

        if ($request->user_ids) {
            $tag_user_id = explode(',', $request->user_ids);
            $tag_users = User::select('id', 'username', 'fcm_token')->whereIn('id', $tag_user_id)->get();

            if ($tag_users) {
                foreach ($tag_users as $tagUser) {

                    $notification                  = new Notification();
                    $notification->user_id         = $tagUser->id;
                    $notification->type            = 'Comments';
                    $notification->post_image_id   = $request->post_image_id;
                    $notification->comment_id      = $comment->id;
                    $notification->tag_user        = $request->user_ids;
                    $notification->notification = auth()->user()->username . ' mentioned you in a comment.';
                    $notification->save();

                    $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'comment_id', 'reply_comment_id', 'is_read')->where('id', $notification->id)->first();

                    if ($tagUser && $tagUser->fcm_token) {
                        $this->notificationService->sendNotification(
                            'Comments',
                            auth()->user()->username . ' mentioned you in a comment.',
                            $tagUser->fcm_token,
                            json_encode($getnotification)
                        );
                    }
                }
            }
        }

        $post_comment = PostComment::with('user.userprofile')->where('id', $comment->id)->latest()->get();

        return $this->success(['Comment added successfully'], $post_comment);
    }

    public function likeComment(Request $request)
    {
        $id = $request->comment_id;
        if ($id) {
            $comment =  PostComment::with('user')->where('id', $id)->first();
            if ($comment) {
                $liked_comment = LikePostComment::where('user_id', auth()->id())->where('comment_id', $id)->first();
                if (!$liked_comment) {

                    LikePostComment::create([
                        'user_id' => auth()->id(),
                        'comment_id' => $id
                    ]);

                    if ($comment->user_id != auth()->id()) {
                        $notification               = new Notification();
                        $notification->user_id      = $comment->user_id;
                        $notification->type         = 'Comments';
                        $notification->post_image_id  = $comment->post_image_id;
                        if ($comment->post_comment_id) {
                            $notification->notification = auth()->user()->username . ' just liked your reply.';
                            $notification->comment_id        = $comment->post_comment_id;
                            $notification->reply_comment_id  = $comment->id;
                        } else {
                            $notification->notification = auth()->user()->username . ' just liked your comment.';
                            $notification->comment_id  = $comment->id;
                        }
                        $notification->save();

                        $user_notification_setting = NotificationSetting::where('user_id', $comment->user->id)->first();
                        if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->like_comment == true)) {
                            $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'comment_id', 'reply_comment_id', 'is_read')->where('id', $notification->id)->first();
                            if ($comment->user && $comment->user->fcm_token) {
                                if ($comment->post_comment_id) {
                                    $this->notificationService->sendNotification(
                                        'Comments',
                                        auth()->user()->username . ' just liked your reply.',
                                        $comment->user->fcm_token,
                                        json_encode($getnotification)
                                    );
                                } else {
                                    $this->notificationService->sendNotification(
                                        'Comments',
                                        auth()->user()->username . ' just liked your comment.',
                                        $comment->user->fcm_token,
                                        json_encode($getnotification)
                                    );
                                }
                            }
                        }
                    }
                    return $this->success(['Comment liked successfully!'], []);
                } else {
                    $liked_comment->delete();
                    return $this->success(['Comment unliked successfully!'], []);
                }
            } else {
                return $this->error(['Please enter valid Comment id']);
            }
        } else {
            return $this->error(['Comment id is required']);
        }
    }

    public function deleteComment(Request $request)
    {
        $id = $request->comment_id;
        if ($id) {
            $comment =  PostComment::find($id);
            if ($comment) {
                $comment->delete();
                return $this->success(['Comment deleted successfully!'], []);
            } else {
                return $this->error(['Please enter valid Comment id']);
            }
        } else {
            return $this->error(['Comment id is required']);
        }
    }
}
