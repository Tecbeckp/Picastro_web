<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Mail\ForgotPasswordMail;
use App\Models\AppVersion;
use App\Models\ContactUs;
use App\Models\Content;
use App\Models\Faq;
use App\Models\IsRegistration;
use App\Models\PaymentMethodStatus;
use App\Models\PaypalSubscription;
use App\Models\PostImage;
use App\Models\Report;
use App\Models\StarCamp;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    use ApiResponseTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        $data['total_users'] = User::whereNotIn('id', ['1'])->count();
        $data['total_post']  = PostImage::count();
        $data['total_starcamp']  = StarCamp::count();
        $data['total_report']  = Report::count();
        $data['is_registration'] = IsRegistration::where('id', '1')->first();
        $data['users'] = User::with('userProfile')->latest()->limit('10')->get();
        $paypal_subscription = PaypalSubscription::count();
        $stripe_subscription = DB::select('SELECT COUNT(*) as total FROM subscriptions')[0]->total;
        $paypal_cancel_subscription = PaypalSubscription::where('status', 'Cancel')->count();
        $stripe_cancel_subscription = DB::select('SELECT COUNT(*) as total FROM subscriptions where stripe_status = "canceled" ')[0]->total;
        $paypal_active_subscription = PaypalSubscription::where('status', 'Approved')->count();
        $stripe_active_subscription = DB::select('SELECT COUNT(*) as total FROM subscriptions where stripe_status = "active" ')[0]->total;
        $data['total_subscriptions'] = $paypal_subscription + $stripe_subscription;
        $data['cancel_subscription'] = $paypal_cancel_subscription + $stripe_cancel_subscription;
        $data['active_subscription'] = $paypal_active_subscription + $stripe_active_subscription;
        $data['pending_subscription'] = PaypalSubscription::where('status', 'APPROVAL_PENDING')->count();

        return view('admin.dashboard', compact('data'));
    }

    public function privacyPolicy(Request $request)
    {
        $privacy = Content::where('name', 'privacy Policy')->first();
        return view('admin.privacy_policy', compact('privacy'));
    }

    public function termsConditions(Request $request)
    {
        $terms = Content::where('name', 'Terms and Conditions')->first();
        return view('admin.terms-condition', compact('terms'));
    }

    public function security(Request $request)
    {
        $security = Content::where('name', 'Security')->first();
        return view('admin.security', compact('security'));
    }

    public function help(Request $request)
    {
        $help = Content::where('name', 'Help')->first();
        return view('admin.help', compact('help'));
    }

    public function aboutUs(Request $request)
    {
        $about = Content::where('name', 'About Us')->first();
        return view('admin.about-us', compact('about'));
    }


    public function StoreContent(Request $request)
    {
        $request->validate([
            'content'   => 'required',
            'page_name' => 'required'
        ]);
        if (isset($request->icon) && isset($request->url)) {
            $icons =  $request->icon;
            $urls  =  $request->url;
            $result = array_map(function ($icons, $urls) {
                return [
                    'icon' => $icons,
                    'link' => $urls
                ];
            }, $icons, $urls);
            $link = json_encode($result);
        } else {
            $link = null;
        }

        Content::updateOrCreate(
            [
                'name' => $request->page_name
            ],
            [
                'content' => $request->content,
                'links'    => $link
            ]
        );

        return redirect()->back()->with('success', 'Update successfully.');
    }

    public function faq()
    {
        $faqs = Faq::all();
        return view('admin.faq', compact('faqs'));
    }
    public function faqDelete($id)
    {

        $data = Faq::find($id);
        if ($data) {
            $data->delete();
            return redirect()->back()->with('success', 'Deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again');
        }
    }

    public function faqUpdate(Request $request)
    {
        $request->validate([
            'faq_id'   => 'required',
            'title'   => 'required',
            'content' => 'required',
            'status' => 'required'
        ]);

        Faq::where('id', $request->faq_id)->update([
            'title'       => $request->title,
            'description' => $request->content,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function StoreFaqContent(Request $request)
    {
        $request->validate([
            'title'     => 'required',
            'content'   => 'required',
            'status'    => 'required'
        ]);

        Faq::create([
            'title'       => $request->title,
            'description' => $request->content,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Create successfully.');
    }

    public function allowRegistration(Request $request)
    {
        if ($request->status == 'true') {
            $status = '1';
        } else {
            $status = '0';
        }
        if ($request->platform_type == 'ios') {
            IsRegistration::where('id', '1')->update([
                'ios' => $status
            ]);
        } elseif ($request->platform_type == 'android') {
            IsRegistration::where('id', '1')->update([
                'android' => $status
            ]);
        } elseif ($request->platform_type == 'ios_screenshot') {
            IsRegistration::where('id', '1')->update([
                'ios_screenshot' => $status
            ]);
        } elseif ($request->platform_type == 'android_screenshot') {
            IsRegistration::where('id', '1')->update([
                'android_screenshot' => $status
            ]);
        }
        return $this->success(['Successfully'], $status);
    }

    public function appVersion(Request $request)
    {

        $data = AppVersion::where('id', '1')->first();
        return view('admin.app_version', compact('data'));
    }

    public function paymentStatus(Request $request)
    {

        $data = PaymentMethodStatus::where('id', '1')->first();
        return view('admin.payment_status', compact('data'));
    }
    public function storeAppVersion(Request $request)
    {
        $request->validate([
            'ios_version'     => 'required',
            'android_version' => 'required'
        ]);

        AppVersion::where('id', 1)->update([
            'ios_version' => $request->ios_version,
            'android_version' => $request->android_version
        ]);

        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function updatePaymentStatus(Request $request)
    {
        if (isset($request->paypal_android)) {
            $paypal_android = $request->paypal_android;
        } else {
            $paypal_android = '0';
        }
        if (isset($request->stripe_android)) {
            $stripe_android = $request->stripe_android;
        } else {
            $stripe_android = '0';
        }
        if (isset($request->paypal_ios)) {
            $paypal_ios = $request->paypal_ios;
        } else {
            $paypal_ios = '0';
        }
        if (isset($request->stripe_ios)) {
            $stripe_ios = $request->stripe_ios;
        } else {
            $stripe_ios = '0';
        }
        PaymentMethodStatus::where('id', 1)->update([
            'paypal_android' => $paypal_android,
            'stripe_android' => $stripe_android,
            'paypal_ios'     => $paypal_ios,
            'stripe_ios'     => $stripe_ios
        ]);

        return redirect()->back()->with('success', 'Updated successfully.');
    }
    public function viewTerms()
    {
        $terms = Content::where('name', 'Terms and Conditions')->first();
        return view('terms-and-conditions', compact('terms'));
    }

    public function viewPrivacy()
    {
        $privacy = Content::where('name', 'privacy Policy')->first();
        return view('privacy-and-policy', compact('privacy'));
    }

    public function getSubscriptionData()
    {
        $subscriptions = DB::table('paypal_subscriptions')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as active'),
                DB::raw('SUM(CASE WHEN status = "Cancel" THEN 1 ELSE 0 END) as canceled'),
                DB::raw('SUM(CASE WHEN status = "APPROVAL_PENDING" THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('month')
            ->union(
                DB::table('subscriptions')
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(CASE WHEN stripe_status = "active" THEN 1 ELSE 0 END) as active'),
                        DB::raw('SUM(CASE WHEN stripe_status = "canceled" THEN 1 ELSE 0 END) as canceled'),
                        DB::raw('0 as pending')
                    )
                    ->groupBy('month')
            )
            ->get();
                        // dd($subscriptions);

        return response()->json([
            'subscriptions' => $subscriptions
        ]);
    }

    // public function contactUs(){
    //     return view('')
    // }
    public function sendEmail(Request $request){
        
        $subject = $request->is_from_register == 'true' ? 'Picastro Email Verification' : 'Forgot Password';
 
     $details = [
         'email'             => $request->email,
         'otp'               => $request->otp,
         'is_from_register'  => $request->is_from_register,
         'subject'           => $subject
     ];
 
     Mail::to($details['email'])->send(new ForgotPasswordMail($details));
 
     return response()->json(['message' => 'Email sent successfully.'], 200);
     }

     public function contactUsMail(Request $request){
     
     $details = [
         'name'     => $request->name,
         'email'    => $request->email,
         'message'  => $request->message
     ];
 
     Mail::to('support@picastroapp.com')->send(new ContactUsMail($details));
 
     return response()->json(['message' => 'Email sent successfully.'], 200);
     }
}
