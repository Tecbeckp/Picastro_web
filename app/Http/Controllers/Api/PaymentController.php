<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Helpers\WebPaymentHelper;
use App\Models\PaypalSubscription;

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

 public function createPlan(){
       $data = $this->paymentHelper->createPlan('PROD-33S42520L4393703N','picastro','48');
       dd($data);
    }
    
    public function createWebHook()
    {
       $data = $this->paymentHelper->createWebHook();
       dd($data);
    }
    public function create(Request $request, string $plan_id = 'P-49K01042X4110980HM3GW7XQ')
    {
        // P-44X84743BV816410HM3GGGUQ
        $id = $request->user_id;
        $this->paymentHelper->subscribeToPlan($plan_id,$id);
        $subscriptionResponse =   $this->paymentHelper->getSubscriptionResponse();
        // Get the approval link
       $link = $this->paymentHelper->redirectUrl($subscriptionResponse['links'],'approve');

       PaypalSubscription::create([
        'user_id' => $id,
        'plan_id' => $subscriptionResponse['plan_id'],
        'subscription_id' => $subscriptionResponse['id'],
        'quantity' => $subscriptionResponse['quantity'],
        'status' => $subscriptionResponse['status'],
        'link' => $link,
       ]);
        
        return redirect()->away($link);
    }

    public function paypalSubscribed(Request $request, $id){
        
        User::where('id',$id)->update([
            'subscription' => '1'
        ]);

        PaypalSubscription::where('user_id',$id)->where('subscription_id',$request->subscription_id)->update([
            'status' => 'Approved'
        ]);
        return $this->success(['successfully subscribed'],[]);

    }
    
    public function paypalsubscriptionCancel(Request $request,$id){
        $subscription_id = $request->subscription_id;
      $this->paymentHelper->cancelSubscription($subscription_id);
        User::where('id',$id)->update([
            'subscription' => '0'
        ]);
        PaypalSubscription::where('user_id',$id)->where('subscription_id',$subscription_id)->update([
            'status' => 'Cancel'
        ]);
        return $this->success(['successfully cancel'],[]);

    }
    
    public function storeSubscription(Request $request, $plan = 'price_1PlAVhICvNFT82L6KBqjG1Pz')
    {

        $user = User::where('id',$request->user_id)->first();
        // $pll = ''; prod_QjWAuSh9HNzXEc
        // $pll = 'price_1Ps38NICvNFT82L6uSUKhcI4';
        if($user){
            return $user->newSubscription('prod_QjpxsciYeXTlOJ','price_1PsMHaICvNFT82L64WkylNIV')
        ->checkout([
            'success_url' => url('subscribed/'.$user->id),
            'cancel_url' => url('subscription-cancel/'.$user->id)
        ]);
        
        }else{
            return $this->error(['User Not Found.']);
        }

    }

    public function subscribed($id){

        User::where('id',$id)->update([
            'subscription' => '1'
        ]);

        return $this->success(['successfully subscribed'],[]);
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
    
   $user = PaypalSubscription::where('user_id',$id)->where('status','Approved')->first();

    if($user){
        $subscription_id = $user->subscription_id;
        $this->paymentHelper->cancelSubscription($subscription_id);
          User::where('id',$id)->update([
              'subscription' => '0'
          ]);
          PaypalSubscription::where('user_id',$id)->where('subscription_id',$subscription_id)->update([
              'status' => 'Cancel'
          ]);
    }else{
         $user = User::where('id',$id)->first();

    $subscription = $user->subscription('prod_QjpxsciYeXTlOJ'); // Use the same name as when creating the subscription

    if ($subscription) {
        $subscription->cancelNow();
    }
        User::where('id',$id)->update([
            'subscription' => '0'
        ]);
    }
        return $this->success(['successfully cancel'],[]);       

    }
    
    public function paypalWebhook(Request $request){

        $webhookData = $request->all();
        $eventType  = $webhookData["event_type"];
        $sid         = $webhookData['resource']['id'];

       
    if ($eventType == WebPaymentHelper::BILLING_EVENT_ACTIVATED) {
        PaypalSubscription::query()->where('subscription_id', $sid)->update([
            'status' => 'Approved'
        ]); 
    } else {
        if (WebPaymentHelper::BILLING_EVENT_CANCELLED) {
            PaypalSubscription::query()->where('subscription_id', $sid)->update([
                'status' => 'Cancelled'
            ]);
        } elseif (WebPaymentHelper::BILLING_EVENT_PAYMENT_FAILED) {
            PaypalSubscription::query()->where('subscription_id', $sid)->update([
                'status' => 'Failed'
            ]);
        } elseif (WebPaymentHelper::BILLING_EVENT_SUSPENDED) {
            PaypalSubscription::query()->where('subscription_id', $sid)->update([
                'status' => 'Suspended'
            ]);
        } else if (WebPaymentHelper::BILLING_EVENT_RE_ACTIVATED) {
            PaypalSubscription::query()->where('subscription_id', $sid)->update([
                'status' => 'Re-Activated'
            ]);

        } elseif (WebPaymentHelper::BILLING_EVENT_RENEWED) {
            PaypalSubscription::query()->where('subscription_id', $sid)->update([
                'status' => 'Renewed'
            ]);
        }
    }
    }
}
