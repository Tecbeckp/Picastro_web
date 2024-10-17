<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Stripe\Stripe;
use Stripe\Coupon;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $search = $request->search;
            $date   = $request->date;
    
            $starcamps = Coupons::query();
            if($search){
                $starcamps->where('coupon_code', 'LIKE', '%'.$search.'%');
            }
            if($date){
                $starcamps->where('created_at', '>=', date('Y-m-d 00:00:00',strtotime($date)))->where('created_at', '<=', date('Y-m-d 23:59:59',strtotime($date)));
            }
            $starcamps->latest();
            $rowid = 0;
    
            return DataTables::of($starcamps)->addIndexColumn()
            ->addColumn('ID', function ($row) use(&$rowid) {
                $rowid++;
                return $rowid;
             })
                ->addColumn('date', function ($row) {
                    return date('d M, Y', strtotime($row->expires_at));
                })
                ->addColumn('status', function ($row) {
                    if($row->status == 'enabled'){
                        $status = '<span class="badge bg-success-subtle text-success text-uppercase">Enabled</span>';
                    }else{
                        $status = '<span class="badge bg-warning-subtle text-warning text-uppercase">Disabled</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $ID  = encrypt($row->id);
                    $btn = '<ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        <a href="coupon/' . $ID .'/edit" class="text-primary d-inline-block edit-item-btn">
                                                            <i class="ri-edit-fill fs-16"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                        <a href="javascript:void(0)" class="text-danger d-inline-block remove-item-btn" onclick="deleteConfirmation(\'' . $ID . '\')">
                                                            <i class="ri-delete-bin-5-fill fs-16"></i>
                                                        </a>
                                                    </li>
                                                </ul>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }else{
            return view('admin.coupon.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|unique:coupons',
            'expire'   => ['required', 'date', 'after_or_equal:today'],
            'discount' => 'required',
            'coupon_status' => 'required',
            'coupon_type' => 'required'
        ], [
            'expire.after_or_equal' => 'The expiration date must be today or a future date.',
            'code.unique'           => 'Coupon code has already been taken.'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {

            if($request->coupon_type == 'percentage'){
                $coupon_type  = 'percent_off';
            }else{
                $coupon_type  = 'amount_off'; 
            }

            $data = Coupon::create([
                'id'            => $request->code,
                'duration'      => 'once',
                'currency'       => 'GBP',
                $coupon_type    => $request->discount,
                'redeem_by'     => strtotime($request->expire)
            ]);

            if($data){
            $coupon = Coupons::create([
                'code' => $request->code,
                'discount'  => $request->discount,
                'type'  => $request->coupon_type,
                'status'  => $request->coupon_status,
                'expires_at'  => $request->expire
            ]);
        }
            return redirect()->route('coupon.index')->with('success', 'Created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Coupons::find(decrypt($id));

        return view('admin.coupon.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code'     => 'required',
            'expire'   => ['required', 'date', 'after_or_equal:today'],
            'discount' => 'required',
            'coupon_status' => 'required',
            'coupon_type' => 'required'
        ], [
            'expire.after_or_equal' => 'The expiration date must be today or a future date.',
            'code.unique'           => 'Coupon code has already been taken.'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $data = Coupons::where('id',decrypt($id))->first();
        dd('yes');
         $coupon = Coupon::retrieve($data->code);
         if($coupon){
            if($request->coupon_type == 'percentage'){
                $coupon_type  = 'percent_off';
            }else{
                $coupon_type  = 'amount_off'; 
            }

            $coupon->update([
                'id'            => $request->code,
                'duration'      => 'once',
                'currency'       => 'GBP',
                $coupon_type    => $request->discount,
                'redeem_by'     => strtotime($request->expire)
            ]);
         }
    }

    public function getCoupon($couponId)
    {
        // Set the Stripe secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve the coupon
            $coupon = Coupon::retrieve($couponId);

            return response()->json([
                'success' => true,
                'coupon' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $data = Coupons::where('id',decrypt($id))->first();
       
        if($data){
            $coupon = Coupon::retrieve($data->code);
            $coupon->delete();
            $data->delete();
            return $this->success('Coupon deleted successfully' ,'');
        }else{
            return $this->error('Something went wrong please try again.');
        }
       
    }
}
