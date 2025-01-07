<?php

namespace App\Helpers;


use App\Models\DuePayments;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mockery\Exception;
use Illuminate\Http\Request;


class WebPaymentHelper
{
    private string $clientId, $clientSecret, $url;
    const BILLING_EVENT_ACTIVATED = 'BILLING.SUBSCRIPTION.ACTIVATED';
    const BILLING_EVENT_CANCELLED = 'BILLING.SUBSCRIPTION.CANCELLED';
    const BILLING_EVENT_EXPIRED = 'BILLING.SUBSCRIPTION.EXPIRED';
    const BILLING_EVENT_PAYMENT_FAILED = 'BILLING.SUBSCRIPTION.PAYMENT.FAILED';
    const BILLING_EVENT_RE_ACTIVATED = 'BILLING.SUBSCRIPTION.RE-ACTIVATED';
    const BILLING_EVENT_RENEWED = 'BILLING.SUBSCRIPTION.RENEWED';
    const BILLING_EVENT_SUSPENDED = 'BILLING.SUBSCRIPTION.SUSPENDED';
    const EVENT_TYPES = [
        ['name' => self::BILLING_EVENT_ACTIVATED],
        ['name' => self::BILLING_EVENT_CANCELLED],
        ['name' => self::BILLING_EVENT_EXPIRED],
        ['name' => self::BILLING_EVENT_PAYMENT_FAILED],
        ['name' => self::BILLING_EVENT_RE_ACTIVATED],
        ['name' => self::BILLING_EVENT_RENEWED],
        ['name' => self::BILLING_EVENT_SUSPENDED],
    ];

    private $subscription_response;


    public function __construct()
    {
        $mode = config('paypal.mode');
        $this->clientId = config('paypal.' . $mode . '.client_id');
        $this->clientSecret = config('paypal.' . $mode . '.client_secret');
        // $this->clientId ='Aa-RpUcXng02PEDCCRTTGjhQa0vOwlFzR0FRa1XaHPphJIenNM8Ev3G-TyoEvOc-Mh4xaMNrdJ52-yR-';
        // $this->clientSecret ='EO0kul6ha8gTby8AAAb6lSq5ajIPrAdMgR9xI7ZV3i1I9NfYFvMy8s-lrvBnFTQqZhkzrpsZbiNKZv3d';


        $this->url = "https://api-m.sandbox.paypal.com";
        if (config('paypal.mode') == "live") {
            $this->url = "https://api-m.paypal.com";
        }
    }

    /**
     * @param $due_payment_id
     * @return mixed
     */
    public function createPayment($due_payment_id): mixed
    {
        $uniqId = Str::uuid()->toString();
        $cancelUrl = route('due-payments.cancel-redirect', ['request_id' => $uniqId, 'due_payment_id' => $due_payment_id]);
        $successUrl = route('due-payments.success-redirect', ['request_id' => $uniqId, 'due_payment_id' => $due_payment_id]);

        $due_payment = DuePayments::query()->where('payment_id', $due_payment_id)->first();
        if (empty($due_payment)) {
            throw new \Exception("Due Payment doesn't exists");
        }
        $token = $this->paypalToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'PayPal-Request-Id' => $uniqId,
        ])->withToken($token)
            ->post($this->url . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => Str::uuid()->toString(),
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $due_payment->amount,
                        ],
                    ],
                ],
                'payment_source' => [
                    'paypal' => [
                        'attributes' => [
                            'vault' => [
                                'store_in_vault' => "ON_SUCCESS",
                                'usage_type' => "MERCHANT"
                            ],

                        ],
                        'experience_context' => [
                            'brand_name' => 'Picastro',
                            'locale' => 'en-US',
                            'user_action' => 'PAY_NOW',
                            'return_url' => $successUrl,
                            'cancel_url' => $cancelUrl,
                        ],
                    ],
                ],
            ]);

        // Handle the response
        $resdata = ($response->json());
        Log::debug("{{{}{{}{}{}{}Paypal Start{}{}{}{}{}}}");
        Log::debug(json_encode($resdata));
        Log::debug("{{{}{{}{}{}{}Paypal End{}{}{}{}{}}}");
        return $this->redirectUrl($resdata['links']);
    }

    public function redirectUrl($links = [], $link_type = "payer-action")
    {
        $url = null;
        foreach ($links as $link) {
            if ($link['rel'] == $link_type) {
                $url = $link['href'];
                break;
            }
        }
        if (empty($url)) {
            throw new Exception("Url cannot be fetched");
        }

        return $url;
    }


    private function paypalToken()
    {
        $body = [
            'grant_type' => 'client_credentials'
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->withBasicAuth($this->clientId, $this->clientSecret)->asForm()
            ->post($this->url . '/v1/oauth2/token', $body);
        $responseData = $response->json();
        if (isset($responseData['access_token'])) {
            return $responseData['access_token'];
        }
        throw new \Exception($response->body());
    }

    public function executePayment($token)
    {
        $access_token = $this->paypalToken();

        $url = $this->url . '/v2/checkout/orders/' . $token . "/capture";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $access_token"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (empty($response)) {
            die(curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function createProduct($name): mixed
    {
        $access_token = $this->paypalToken();
        $response = Http::withToken($access_token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',

                'Prefer' => 'return=representation',
            ])
            ->post($this->url . '/v1/catalogs/products', [
                'name' => $name . ' Subscription Product',
                'description' => 'Picastro Subscription Products',
                'type' => 'SERVICE',
                'category' => 'SOFTWARE',
            ]);

        return $response->json()['id'];
    }

    public function createPlan($productId, $name, $subscriptionPrice, $planCycle = "YEAR")
    {
        $access_token = $this->paypalToken();
        $response = Http::withToken($access_token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Prefer' => 'return=representation',
            ])
            ->post($this->url . '/v1/billing/plans', [
                'product_id' => $productId,
                'name' => $name . ' Subscription Plan',
                'description' => 'Picastro Subscription plan',
                'status' => 'ACTIVE',
                'billing_cycles' => [
                    [
                        'frequency' => [
                            "interval_unit" => "MONTH",
                            "interval_count" => 6
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => 0, // 0 indicates an infinite cycle (bills indefinitely every year)
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $subscriptionPrice, // Replace with your desired yearly price
                                'currency_code' => 'GBP',
                            ],
                        ],
                    ],
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'payment_failure_threshold' => 3,
                ],
            ]);

        // To view the response
        return $response->json()['id'];
    }

    public function updatePlan($planId, $subscriptionPrice)
    {
        $access_token = $this->paypalToken();


        $response = Http::withToken($access_token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->url . '/v1/billing/plans/' . $planId . '/update-pricing-schemes', [
                'pricing_schemes' => [
                    [
                        'billing_cycle_sequence' => 1,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $subscriptionPrice,
                                'currency_code' => 'USD',
                            ],
                        ],
                    ],
                ],
            ]);


        // To view the response
        return $response->json();
    }

    /**
     * @return WebPaymentHelper $this
     * @throws \Exception
     */
    public function subscribeToPlan($planId, $id, $plan_id)
    {
        if (empty($planId)) {
            throw  new \Exception("Plan Is required");
        }
        $subscriber = User::where('id', $id)->first();
        $token = $this->paypalToken();
        $response = Http::withToken($token)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Prefer' => 'return=representation',
        ])->post($this->url . '/v1/billing/subscriptions', [
            "plan_id" => $planId,
            "quantity" => "1",
            // "shipping_amount" => [
            // "currency_code" => "GBP",
            // "value" => $setupfee
            // ],
            "subscriber" => [
                "name" => [
                    "given_name" => $subscriber->first_name,
                    "surname" => $subscriber->last_name
                ],
                "email_address" => $subscriber->email,
            ],
            "application_context" => [
                "brand_name" => "Picastro",
                "locale" => "en-US",
                "user_action" => "SUBSCRIBE_NOW",
                "payment_method" => [
                    "payer_selected" => "PAYPAL",
                    "payee_preferred" => "IMMEDIATE_PAYMENT_REQUIRED"
                ],
                "return_url" => url('paypal-subscribed/' . $id . '/' . $plan_id),
                "cancel_url" => url('paypal-subscription-cancel/' . $id)
            ]
        ]);

        if ($response->successful()) {
            // Handle successful response
            $this->subscription_response = $response->json();
        } else {
            // Handle failed request
            $this->subscription_response = $response->json();
        }
        return $this;
    }

    public function getSubscriptionResponse()
    {
        return $this->subscription_response;
    }

    public function getSubscriptionDetails($id)
    {

        $response = Http::withToken($this->paypalToken())->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($this->url . '/v1/billing/subscriptions/' . $id);

        if ($response->successful()) {
            // Handle successful response
            return $response->json();
        } else {
            // Handle failed request
            return $response->body();
        }
    }

    public function cancelSubscription($sid)
    {

        $response = Http::withToken($this->paypalToken())->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->url . '/v1/billing/subscriptions/' . $sid . '/cancel', [
            'reason' => 'Auto Reinitializing'
        ]);

        if ($response->successful()) {
            // Handle successful response
            return $response->json();
        } else {
            // Handle failed request
            return $response->body();
        }
    }

    public function createWebHook()
    {

        $response = Http::withToken($this->paypalToken())->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->url . '/v1/notifications/webhooks', [
            'url' => route('paypal.subscription.webhook'),
            'event_types' => self::EVENT_TYPES,
        ]);

        if ($response->successful()) {
            // Handle successful response
            return $response->json();
        } else {
            // Handle failed request
            return $response->json();
        }
    }

    public function listAvailableWebHookEvents()
    {
        $response = Http::withToken($this->paypalToken())->withHeaders([
            'Content-Type' => 'application/json',

        ])->get($this->url . '/v1/notifications/webhooks-event-types');

        if ($response->successful()) {
            // Handle successful response
            return $response->json();
        } else {
            // Handle failed request
            return $response->body();
        }
    }
}

function web_payment(): WebPaymentHelper
{
    return new WebPaymentHelper();
}
