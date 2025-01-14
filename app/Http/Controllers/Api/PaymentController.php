<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Helpers\WebPaymentHelper;
use App\Mail\giftMail;
use App\Models\Coupons;
use App\Models\PaypalSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Coupon;

class PaymentController extends Controller
{
    use ApiResponseTrait;
    protected $provider;
    protected $paymentHelper;

    public function __construct()
    {
        $this->paymentHelper = new WebPaymentHelper();
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function createProduct($name)
    {
        $data = $this->paymentHelper->createProduct($name);
        dd($data);
    }

    public function createPlan(Request $request)
    {

        $data = $this->paymentHelper->createPlan($request->product_id, $request->product_name, $request->product_price);
        dd($data);
    }

    public function createWebHook()
    {
        $data = $this->paymentHelper->createWebHook();
        dd($data);
    }
    public function create(Request $request)
    {
        $mode = config('paypal.mode');
        // string $plan_id = 'P-1075760685626815NM3RO7BI'
        // P-44X84743BV816410HM3GGGUQ
        if ($request->gifted_plan_user_id) {
            $id = $request->gifted_plan_user_id;
        } else {
            $id = $request->user_id;
        }
        $subscription_plan = SubscriptionPlan::where('id', $request->plan_id)->first();
        if ($subscription_plan) {
            if ($mode == 'sandbox') {
                $paypal_plan =  $subscription_plan->test_paypal_price_id;
            } else {
                $paypal_plan = $subscription_plan->paypal_price_id;
            }
            $this->paymentHelper->subscribeToPlan($paypal_plan, $id, $subscription_plan->id);
            $subscriptionResponse =   $this->paymentHelper->getSubscriptionResponse();
            // Get the approval link
            $link = $this->paymentHelper->redirectUrl($subscriptionResponse['links'], 'approve');

            PaypalSubscription::create([
                'user_id' => $id,
                'plan_id' => $subscriptionResponse['plan_id'],
                'subscription_id' => $subscriptionResponse['id'],
                'quantity' => $subscriptionResponse['quantity'],
                'status' => $subscriptionResponse['status'],
                'link' => $link,
            ]);

            return redirect()->away($link);
        } else {
            return $this->error(['Internal server error.']);
        }
    }

    public function paypalSubscribed(Request $request, $id, $plan_id)
    {
        User::where('id', $id)->update([
            'subscription' => '1',
            'subscription_id' => $plan_id
        ]);

        $user = User::where('id', $id)->first();
        if ($user && $user->trial_period_status == '2') {
            User::where('id', $id)->update([
                'trial_period_status' => '0'
            ]);
        }
        PaypalSubscription::where('user_id', $id)->where('subscription_id', $request->subscription_id)->update([
            'status' => 'Approved'
        ]);
        if ($user->gift_subscription != null) {
            Mail::to($user->email)->send(new giftMail());
        }
        return $this->success(['successfully subscribed'], []);
    }

    public function paypalsubscriptionCancel(Request $request, $id)
    {
        $subscription_id = $request->subscription_id;
        $this->paymentHelper->cancelSubscription($subscription_id);
        User::where('id', $id)->update([
            'subscription' => '0'
        ]);
        PaypalSubscription::where('user_id', $id)->where('subscription_id', $subscription_id)->update([
            'status' => 'Cancel'
        ]);
        return $this->success(['successfully cancel'], []);
    }

    public function storeSubscription(Request $request, $plan = 'price_1PyCs1ICvNFT82L6mq4xFwRk')
    {
        if ($request->gifted_plan_user_id) {
            $user_id = $request->gifted_plan_user_id;
        } else {
            $user_id = $request->user_id;
        }
        Stripe::setApiKey(config('services.stripe.secret'));



        $user = User::where('id', $user_id)->first();
        $subscription_plan = SubscriptionPlan::where('id', $request->plan_id)->first();
        if ($user && $subscription_plan) {
            // $pll = ''; prod_QjWAuSh9HNzXEc
            // $pll = 'price_1Ps38NICvNFT82L6uSUKhcI4';
            if (config('services.stripe.mode') == 'test') {
                $stripe_plan_id = $subscription_plan->test_stripe_price_id;
            } else {
                $stripe_plan_id = $subscription_plan->stripe_price_id;
            }
            $customer = $user->stripe_id
                ? Customer::retrieve($user->stripe_id)
                : Customer::create([
                    'email' => $user->email,
                ]);

            User::where('id', $user_id)->update([
                'stripe_id' => $customer->id
            ]);

            $successUrl = url('subscribed/' . $user->id . '/' . $subscription_plan->id);
            $cancelUrl  = url('subscription-cancel/' . $user->id);

            $checkoutSessionData = [
                'customer' => $customer->id,
                'payment_method_types' => [
                    'card',
                ],
                'line_items' => [[
                    'price'      => $stripe_plan_id,
                    'quantity'   => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ];
            if ($request->filled('coupon_code')) {
                $coupon = Coupons::where('code', $request->coupon_code)->where('status', 'enabled')->first();

                if ($coupon) {
                    if ($coupon->expires_at >= now()->format('Y-m-d')) {
                        // Retrieve the promotion code object from Stripe
                        $promotionCode = Coupon::retrieve($request->coupon_code);
                        if (!empty($promotionCode)) {
                            // Use the Stripe promotion_code ID
                            $checkoutSessionData['discounts'] = [
                                ['coupon' => $promotionCode->id]
                            ];
                        } else {
                            return $this->error(['Invalid or inactive promotion code.']);
                        }
                    } else {
                        return $this->error(['This coupon has expired.']);
                    }
                } else {
                    return $this->error(['Invalid coupon code.']);
                }
            }

            $checkoutSession = \Stripe\Checkout\Session::create($checkoutSessionData);
            return Redirect::to($checkoutSession->url);
        } else {
            return $this->error(['Internal server error.']);
        }
    }

    public function subscribed($id, $plan_id)
    {

        User::where('id', $id)->update([
            'subscription' => '1',
            'subscription_id' => $plan_id
        ]);

        $user = User::where('id', $id)->first();
        if ($user && $user->trial_period_status == '2') {
            User::where('id', $id)->update([
                'trial_period_status' => '0'
            ]);
        }
        if ($user->gift_subscription != null) {
            Mail::to($user->email)->send(new giftMail());
        }

        return $this->success(['successfully subscribed'], []);
    }

    public function subscriptionCancel($id)
    {
        //     $user = User::where('id',$id)->first();
        //     //  $subscription = $user->subscription('prod_QjWAuSh9HNzXEc');
        //   $subscription = $user->subscriptions;
        //     dd($subscription);

        // if ($subscription) {
        //     $subscription->cancelNow();
        // }

        $user = PaypalSubscription::where('user_id', $id)->where('status', 'Approved')->first();

        if ($user) {
            $subscription_id = $user->subscription_id;
            $this->paymentHelper->cancelSubscription($subscription_id);
            User::where('id', $id)->update([
                'subscription' => '0'
            ]);
            PaypalSubscription::where('user_id', $id)->where('subscription_id', $subscription_id)->update([
                'status' => 'Cancel'
            ]);
        } else {
            $user = User::where('id', $id)->first();

            $subscription = $user->subscription('prod_QpsdEeUzwiQZeL'); // Use the same name as when creating the subscription

            if ($subscription) {
                $subscription->cancelNow();
            }
            User::where('id', $id)->update([
                'subscription' => '0'
            ]);
        }
        return $this->success(['successfully cancel'], []);
    }

    public function paypalWebhook(Request $request)
    {

        $webhookData = $request->all();
        $eventType  = $webhookData["event_type"];
        $sid         = $webhookData['resource']['id'];

        $subscription = PaypalSubscription::query()->where('subscription_id', $sid)->first();

        if ($eventType == WebPaymentHelper::BILLING_EVENT_ACTIVATED) {
            if ($subscription) {
                $subscription->update([
                    'status' => 'Approved'
                ]);

                User::where('id', $subscription->user_id)->update([
                    'subscription' => '1'
                ]);
            }
        } else {
            if (WebPaymentHelper::BILLING_EVENT_CANCELLED) {
                if ($subscription) {
                    $subscription->update([
                        'status' => 'Cancelled'
                    ]);

                    User::where('id', $subscription->user_id)->update([
                        'subscription' => '0'
                    ]);
                }
            } elseif (WebPaymentHelper::BILLING_EVENT_PAYMENT_FAILED) {
                if ($subscription) {
                    $subscription->update([
                        'status' => 'Failed'
                    ]);

                    User::where('id', $subscription->user_id)->update([
                        'subscription' => '0'
                    ]);
                }
            } elseif (WebPaymentHelper::BILLING_EVENT_SUSPENDED) {
                if ($subscription) {
                    $subscription->update([
                        'status' => 'Suspended'
                    ]);

                    User::where('id', $subscription->user_id)->update([
                        'subscription' => '0'
                    ]);
                }
            } else if (WebPaymentHelper::BILLING_EVENT_RE_ACTIVATED) {
                if ($subscription) {
                    $subscription->update([
                        'status' => 'Re-Activated'
                    ]);

                    User::where('id', $subscription->user_id)->update([
                        'subscription' => '1'
                    ]);
                }
            } elseif (WebPaymentHelper::BILLING_EVENT_RENEWED) {
                if ($subscription) {
                    $subscription->update([
                        'status' => 'Renewed'
                    ]);

                    User::where('id', $subscription->user_id)->update([
                        'subscription' => '1'
                    ]);
                }
            }
        }
    }
}
