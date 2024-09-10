<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StarCard;
use App\Models\StarCardFilter;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StarCardController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $starcards = StarCard::with('StarCardFilter')->where('user_id',auth()->id())->get();
        return $this->success([], $starcards);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'camera_type'           => 'required',
            'setup'                 => 'required',
            'number_of_darks'       => 'required|numeric',
            'number_of_flats'       => 'required|numeric',
            'number_of_dark_flats'  => 'required|numeric',
            'number_of_bias'        => 'required|numeric',
        ];
        if ($request->camera_type == 'OSC camera' || $request->camera_type == 'DSLR') {
            $rules['light_frame_number'] = 'required|numeric';
            $rules['light_frame_exposure'] = 'required|numeric';
        }
        if ($request->camera_type == 'DSLR') {
            $rules['iso']           = 'required|numeric';
            $rules['ratio']      = 'required';
            $rules['focal_length']  = 'required';
        }
        if ($request->camera_type == 'Mono camera') {
            $rules['starcard_filter']                   = 'required|array';
            $rules['starcard_filter.0.filter_name']     = 'required';
            $rules['starcard_filter.0.number_of_subs']  = 'required|numeric';
            $rules['starcard_filter.0.exposure_time']   = 'required|numeric';
            $rules['starcard_filter.0.gain']            = 'required|numeric';
            $rules['starcard_filter.0.binning']         = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $starcard               = new StarCard();
        $starcard->user_id      = auth()->id();
        $starcard->camera_type  = $request->camera_type;
        $starcard->setup        = $request->setup;
        if ($request->camera_type == 'OSC camera' || $request->camera_type == 'DSLR') {
        $starcard->light_frame_number    = $request->light_frame_number;
        $starcard->light_frame_exposure  = $request->light_frame_exposure;
        }
        $starcard->number_of_darks      = $request->number_of_darks;
        $starcard->number_of_flats      = $request->number_of_flats;
        $starcard->number_of_dark_flats = $request->number_of_dark_flats;
        $starcard->number_of_bias       = $request->number_of_bias;
        if ($request->camera_type == 'DSLR') {
        $starcard->iso          = $request->iso;
        $starcard->ratio        = $request->ratio;
        $starcard->focal_length = $request->focal_length;
        }
        $starcard->save();

        if ($request->camera_type == 'Mono camera') {
            foreach($request->starcard_filter as $filter){
                $starcard_filter                 = new StarCardFilter();
                $starcard_filter->star_card_id   = $starcard->id;
                $starcard_filter->name           = $filter['filter_name'];
                $starcard_filter->number_of_subs = $filter['number_of_subs'];
                $starcard_filter->exposure_time  = $filter['exposure_time'];
                $starcard_filter->gain           = $filter['gain'];
                $starcard_filter->binning        = $filter['binning'];
                $starcard_filter->save();
            }
        }

        return $this->success(['Success, StarCard has been uploaded'], []);
 
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
        $starcard = StarCard::with('StarCardFilter')->where('user_id',auth()->id())->where('id',$id)->first();
        return $this->success([], $starcard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'camera_type'           => 'required',
            'setup'                 => 'required',
            'number_of_darks'       => 'required|numeric',
            'number_of_flats'       => 'required|numeric',
            'number_of_dark_flats'  => 'required|numeric',
            'number_of_bias'        => 'required|numeric',
        ];
        if ($request->camera_type == 'OSC camera' || $request->camera_type == 'DSLR') {
            $rules['light_frame_number'] = 'required|numeric';
            $rules['light_frame_exposure'] = 'required|numeric';
        }
        if ($request->camera_type == 'DSLR') {
            $rules['iso']           = 'required|numeric';
            $rules['ratio']      = 'required';
            $rules['focal_length']  = 'required';
        }
        if ($request->camera_type == 'Mono camera') {
            $rules['starcard_filter']                  = 'required|array';
            $rules['starcard_filter.0.filter_name']    = 'required';
            $rules['starcard_filter.0.number_of_subs'] = 'required|integer';
            $rules['starcard_filter.0.exposure_time']  = 'required|integer';
            $rules['starcard_filter.0.gain']           = 'required|integer';
            $rules['starcard_filter.0.binning']        = 'required|integer';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }
        try{

        $data = [
            'camera_type'           => $request->camera_type,
            'setup'                 => $request->setup,
            'number_of_darks'       => $request->number_of_darks,
            'number_of_flats'       => $request->number_of_flats,
            'number_of_dark_flats'  => $request->number_of_dark_flats,
            'number_of_bias'        => $request->number_of_bias,
        ];
        if ($request->camera_type == 'OSC camera' || $request->camera_type == 'DSLR') {
            $data['light_frame_number']   = $request->light_frame_number;
            $data['light_frame_exposure'] = $request->light_frame_exposure;
        }
        if ($request->camera_type == 'DSLR') {
            $data['iso']          = $request->iso;
            $data['ratio']        = $request->ratio;
            $data['focal_length'] = $request->focal_length;
        }
        StarCard::where('user_id',auth()->id())->where('id',$id)->update($data);

        if ($request->camera_type == 'Mono camera') {
            foreach($request->starcard_filter as $filter){
            StarCardFilter::where('star_card_id',$id)->where('name',$filter['filter_name'])->update([
                'number_of_subs' => $filter['number_of_subs'],
                'exposure_time'  => $filter['exposure_time'],
                'gain'           => $filter['gain'],
                'binning'        => $filter['binning']
            ]);
            }
        }

        return $this->success(['Success, StarCard has been updated'], []);
        
    } catch (ValidationException $e) {
        return $this->error($e->errors());
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $starcard =  StarCard::find($id);
        if($starcard){
            $starcard->delete();
            return $this->success(['StarCard has been deleted'], []);
        }else{
            return $this->error(['Something went wrong']);
        }

    }
}
