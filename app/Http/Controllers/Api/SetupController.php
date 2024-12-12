<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainSetup;
use App\Models\Setup;
use App\Models\SetupFilter;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class SetupController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $setups = MainSetup::select('id','name')->where('user_id',auth()->id())->get();
        return $this->success([], $setups);

    }

    public function store(Request $request)
    {
        $rules = [
            // 'camera_type'           => 'required',
            // 'number_of_darks'       => 'required|numeric|min:0',
            // 'number_of_flats'       => 'required|numeric|min:0',
            // 'number_of_dark_flats'  => 'required|numeric|min:0',
            // 'number_of_bias'        => 'required|numeric|min:0',
            'setup_name'          => 'required|unique:main_setups,name',
            // 'telescope_name'      => 'required',
            // 'scope_type'          => 'required',
            // 'mount_name'          => 'required',
            // 'camera_lens'         => 'required',
            // 'imaging_camera'      => 'required',
            // 'guide_camera'        => 'required',
            // 'guide_scope'         => 'required',
            // 'filter_wheel'        => 'required',
            // 'reducer_name'        => 'required',
            // 'autofocuser'         => 'required',
            // 'other_accessories'   => 'required',
            // 'barlow_lens'        => 'required',
            // 'filters'             => 'required',
            // 'acquistion_software' => 'required',
            // 'processing'          => 'required'
        ];

            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
            //     $rules['light_frame_number']    = 'required|numeric|min:0|not_negative_zero';
            //     $rules['light_frame_exposure']  = 'required|numeric|min:0|not_negative_zero';
            // }
            // if (strtolower($request->camera_type) == 'dslr') {
            //     $rules['iso']           = 'required|numeric|min:0|not_negative_zero';
            //     $rules['ratio']         = 'required';
            //     $rules['focal_length']  = 'required';
            // }
            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
            //     $rules['cooling']    = 'required|numeric';
            // }
            // if (strtolower($request->camera_type) == 'mono camera') {
            //     $rules['filter_name']     = 'required';
            //     $rules['number_of_subs']  = 'required';
            //     $rules['exposure_time']   = 'required';
            //     $rules['gain']            = 'required';
            //     $rules['binning']         = 'required';
            // }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->error($validator->errors()->all());
            }

            // $totalsetups = MainSetup::select('name')->where('user_id',auth()->id())->count();
            // if($totalsetups >= 3){
            //     return $this->error(['You have already created 3 setups, which is the maximum allowed. Please delete an existing setup if you want to create a new one.']);
            // }

            try{
                // $setups = MainSetup::select('name')->where('user_id',auth()->id())->latest()->first();
                // if(!is_null($setups)){
                //     $set_up = 'Setup '.explode(' ',$setups->name)[1]+1;
                // }else{
                //     $set_up = 'Setup 1';
                // }

            // $setup               = new Setup();
            // $setup->user_id      = auth()->id();
            // $setup->name         = $set_up;
            // $setup->camera_type  = $request->camera_type;
            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
            // $setup->light_frame_number    = $request->light_frame_number;
            // $setup->light_frame_exposure  = $request->light_frame_exposure;
            // }
            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
            //     $setup->cooling    = $request->cooling;
            // }
            // $setup->number_of_darks      = $request->number_of_darks;
            // $setup->number_of_flats      = $request->number_of_flats;
            // $setup->number_of_dark_flats = $request->number_of_dark_flats;
            // $setup->number_of_bias       = $request->number_of_bias;
            // if (strtolower($request->camera_type) == 'dslr') {
            // $setup->iso          = $request->iso;
            // $setup->ratio        = $request->ratio;
            // $setup->focal_length = $request->focal_length;
            // }
            // $setup->save();

            $main_setup = new MainSetup();
            $main_setup->user_id = auth()->id();
            $main_setup->name    = $request->setup_name;
            $main_setup->telescope_name = $request->telescope_name;
            $main_setup->scope_type = $request->scope_type;
            $main_setup->mount_name = $request->mount_name;
            $main_setup->camera_lens = $request->camera_lens;
            $main_setup->imaging_camera = $request->imaging_camera;
            $main_setup->guide_camera = $request->guide_camera;
            $main_setup->guide_scope = $request->guide_scope;
            $main_setup->filter_wheel = $request->filter_wheel;
            $main_setup->reducer_name = $request->reducer_name;
            $main_setup->autofocuser = $request->autofocuser;
            $main_setup->other_accessories = $request->other_accessories;
            $main_setup->barlow_lens = $request->barlow_lens;
            $main_setup->filters = $request->filters;
            $main_setup->acquistion_software = $request->acquistion_software;
            $main_setup->processing = $request->processing;
            $main_setup->save();
    
            // if (strtolower($request->camera_type) == 'mono camera') {
               
            //     $filter_names = json_decode($request->filter_name, true);
            //     $number_of_subs = json_decode($request->number_of_subs, true);
            //     $exposure_times = json_decode($request->exposure_time, true);
            //     $gains = json_decode($request->gain, true);
            //     $binnings = json_decode($request->binning, true);
            
            //     foreach ($filter_names as $index => $filter_name) {
            //         $setup_filter                 = new SetupFilter();
            //         $setup_filter->setup_id       = $setup->id;
            //         $setup_filter->name           = $filter_name;
            //         $setup_filter->number_of_subs = $number_of_subs[$index];
            //         $setup_filter->exposure_time  = $exposure_times[$index];
            //         $setup_filter->gain           = $gains[$index];
            //         $setup_filter->binning        = $binnings[$index];
            //         $setup_filter->save();
            //     }
            // }
            return $this->success(['Setup uploaded successfully!'], $main_setup);

        } catch (ValidationException $e) {
            return $this->error($e->errors());
        }
    }

    public function update(Request $request)
    {
        $rules = [
            // 'camera_type'           => 'required',
            // 'number_of_darks'       => 'required|numeric|min:0',
            // 'number_of_flats'       => 'required|numeric|min:0',
            // 'number_of_dark_flats'  => 'required|numeric|min:0',
            // 'number_of_bias'        => 'required|numeric|min:0',
            'id'                  => 'required|exists:main_setups,id',
            'setup_name'          => 'required',
            // 'telescope_name'      => 'required',
            // 'scope_type'          => 'required',
            // 'mount_name'          => 'required',
            // 'camera_lens'         => 'required',
            // 'imaging_camera'      => 'required',
            // 'guide_camera'        => 'required',
            // 'guide_scope'         => 'required',
            // 'filter_wheel'        => 'required',
            // 'reducer_name'        => 'required',
            // 'autofocuser'         => 'required',
            // 'other_accessories'   => 'required',
            // 'barlow_lens'         => 'required',
            // 'filters'             => 'required',
            // 'acquistion_software' => 'required',
            // 'processing'          => 'required'
        ];
        
            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
            //     $rules['light_frame_number']    = 'required|numeric|min:0|not_negative_zero';
            //     $rules['light_frame_exposure']  = 'required|numeric|min:0|not_negative_zero';
            // }
            // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
            //     $rules['cooling']  = 'required|numeric';
            // }
            // if (strtolower($request->camera_type) == 'dslr') {
            //     $rules['iso']           = 'required|numeric|min:0|not_negative_zero';
            //     $rules['ratio']         = 'required';
            //     $rules['focal_length']  = 'required';
            // }
            // if (strtolower($request->camera_type) == 'mono camera') {
            //     $rules['filter_name']     = 'required';
            //     $rules['number_of_subs']  = 'required';
            //     $rules['exposure_time']   = 'required';
            //     $rules['gain']            = 'required';
            //     $rules['binning']         = 'required';
            // }
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->error($validator->errors()->all());
            }

            $id = $request->id;
            if($id){
                $data = [
                    'name'                => $request->setup_name,
                    'telescope_name'      => $request->telescope_name,
                    'scope_type'          => $request->scope_type,
                    'mount_name'          => $request->mount_name,
                    'camera_lens'         => $request->camera_lens,
                    'imaging_camera'      => $request->imaging_camera,
                    'guide_camera'        => $request->guide_camera,
                    'guide_scope'         => $request->guide_scope,
                    'filter_wheel'        => $request->filter_wheel,
                    'reducer_name'        => $request->reducer_name,
                    'autofocuser'         => $request->autofocuser,
                    'other_accessories'   => $request->other_accessories,
                    'barlow_lens'         => $request->barlow_lens,
                    'filters'             => $request->filters,
                    'acquistion_software' => $request->acquistion_software,
                    'processing'          => $request->processing
                ];
                // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'dslr') {
                  // 'camera_type'           => $request->camera_type,
                    // 'number_of_darks'       => $request->number_of_darks,
                    // 'number_of_flats'       => $request->number_of_flats,
                    // 'number_of_dark_flats'  => $request->number_of_dark_flats,
                    // 'number_of_bias'        => $request->number_of_bias,
                //     $data['light_frame_number']   = $request->light_frame_number;
                //     $data['light_frame_exposure'] = $request->light_frame_exposure;
                // }
                // if (strtolower($request->camera_type) == 'osc camera' || strtolower($request->camera_type) == 'mono camera') {
                //     $data['cooling']    = $request->cooling;
                // }
                // if (strtolower($request->camera_type) == 'dslr') {
                //     $data['iso']          = $request->iso;
                //     $data['ratio']        = $request->ratio;
                //     $data['focal_length'] = $request->focal_length;
                // }
                MainSetup::where('user_id',auth()->id())->where('id',$id)->update($data);
        
                // if (strtolower($request->camera_type) == 'mono camera') {
                //     $filter_names = json_decode($request->filter_name, true);
                //     $number_of_subs = json_decode($request->number_of_subs, true);
                //     $exposure_times = json_decode($request->exposure_time, true);
                //     $gains = json_decode($request->gain, true);
                //     $binnings = json_decode($request->binning, true);

                //     foreach ($filter_names as $index => $filter_name) {
                //         SetupFilter::updateOrCreate([
                //             'setup_id'   => $id,
                //             'name'       => $filter_name
                //         ],[
                //         'number_of_subs' => $number_of_subs[$index],
                //         'exposure_time'  => $exposure_times[$index],
                //         'gain'           => $gains[$index],
                //         'binning'        => $binnings[$index]
                //         ]);
                //     }
                    
                // }
    
            return $this->success(['Setup updated successfully!'], []);

        }else{
            return $this->error(['Please enter valid setup id']);
        }

    }

    public function setupDetail(Request $request){
        $id = $request->id;
        if($id){
            $setup = MainSetup::where('id',$id)->first();
            if($setup){
                $data = [
                    'setup' => $setup
                ];
                return $this->success(['Setup get successfully!'], $data);
            }else{
                return $this->error(['Please enter valid Setup id']);
            }
        }else{
            return $this->error(['Setup id is required']);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        if($id){
            $Setup =  MainSetup::find($id);
            if($Setup){
                $Setup->delete();
                return $this->success(['Setup deleted successfully!'], []);
            }else{
                return $this->error(['Please enter valid Setup id']);
            }
        }else{
            return $this->error(['Setup id is required']);
        }
        
    }
}
