<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Faq;
use App\Models\IsRegistration;
use App\Models\PaypalSubscription;
use App\Models\PostImage;
use App\Models\Report;
use App\Models\StarCamp;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    use ApiResponseTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        $data['total_users'] = User::whereNotIn('id',['1'])->count();
        $data['total_post']  = PostImage::count();
        $data['total_starcamp']  = StarCamp::count();
        $data['total_report']  = Report::count();
        $data['is_registration'] = IsRegistration::where('id','1')->first()->is_registration;
        $data['users'] = User::with('userProfile')->latest()->limit('10')->get();
        $paypal_subscription = PaypalSubscription::count(); 
        $stripe_subscription = DB::select('SELECT COUNT(*) as total FROM subscriptions')[0]->total; 
        $data['total_subscriptions'] = $paypal_subscription + $stripe_subscription;
        return view('admin.dashboard',compact('data'));
    }

    public function privacyPolicy(Request $request)
    {    
        $privacy = Content::where('name','privacy Policy')->first();
        return view('admin.privacy_policy', compact('privacy'));
    }

    public function termsConditions(Request $request)
    {    
        $terms = Content::where('name','Terms and Conditions')->first();
        return view('admin.terms-condition', compact('terms'));
    }

    public function security(Request $request)
    {    
        $security = Content::where('name','Security')->first();
        return view('admin.security', compact('security'));
    }

    public function help(Request $request)
    {    
        $help = Content::where('name','Help')->first();
        return view('admin.help', compact('help'));
    }

 public function aboutUs(Request $request)
    {    
        $about = Content::where('name','About Us')->first();
        return view('admin.about-us', compact('about'));
    }


    public function StoreContent(Request $request){
        $request->validate([
            'content'   => 'required',
            'page_name' => 'required'
        ]);
        if(isset($request->icon) && isset($request->url)){
            $icons =  $request->icon;
            $urls  =  $request->url;
            $result = array_map(function ($icons, $urls) {
                return [
                    'icon' => $icons,
                    'link' => $urls
                ];
            }, $icons, $urls);
            $link = json_encode($result);
        }else{
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
    
    public function faq(){
        $faqs = Faq::all();
        return view('admin.faq', compact('faqs'));
    }
    public function faqDelete($id){

        $data = Faq::find($id);
        if($data){
            $data->delete();
            return redirect()->back()->with('success', 'Deleted successfully.');
        }else{
            return redirect()->back()->with('error', 'Something went wrong. Please try again');
        }
    }

    public function faqUpdate(Request $request){
        $request->validate([
            'faq_id'   => 'required',
            'title'   => 'required',
            'content' => 'required',
            'status' => 'required'
        ]);

        Faq::where('id',$request->faq_id)->update([
            'title'       => $request->title,
            'description' => $request->content,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function StoreFaqContent(Request $request){
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

    public function allowRegistration(Request $request){
        if($request->status == 'true'){
            $status = '1';
        }else{
            $status = '0';        }
        IsRegistration::where('id','1')->update([
            'is_registration' => $status
        ]);
        
        return $this->success(['Successfully'],$status);
    }
    public function viewTerms(){
        $terms = Content::where('name','Terms and Conditions')->first();
        return view('terms-and-conditions', compact('terms'));
    }

    public function viewPrivacy(){
        $privacy = Content::where('name','privacy Policy')->first();
        return view('privacy-and-policy', compact('privacy'));
    }
}
