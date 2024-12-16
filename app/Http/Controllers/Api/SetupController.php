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
        $setups = MainSetup::where('user_id', auth()->id())->get();

        $data = $setups->map(function ($setup, $key) {
            return [
                'id' => $setup->id,
                'name' => $setup->name,
                'data' => [
                    'setup' => [
                        'id' => $setup->id,
                        'user_id' => $setup->user_id,
                        'name' => $setup->name,
                        'telescope_name' => $setup->telescope_name,
                        'scope_type' => $setup->scope_type,
                        'mount_name' => $setup->mount_name,
                        'camera_lens' => $setup->camera_lens,
                        'imaging_camera' => $setup->imaging_camera,
                        'guide_camera' => $setup->guide_camera,
                        'guide_scope' => $setup->guide_scope,
                        'filter_wheel' => $setup->filter_wheel,
                        'reducer_name' => $setup->reducer_name,
                        'autofocuser' => $setup->autofocuser,
                        'other_accessories' => $setup->other_accessories,
                        'barlow_lens' => $setup->barlow_lens,
                        'filters' => $setup->filters,
                        'acquistion_software' => $setup->acquisition_software,
                        'processing' => $setup->processing,
                        'deleted_at' => $setup->deleted_at,
                        'created_at' => $setup->created_at->toISOString(),
                        'updated_at' => $setup->updated_at->toISOString(),
                    ],
                ],
            ];
        });

        return $this->success([], $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'setup_name'               => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $namesetup = MainSetup::where('user_id', auth()->id())->where('name', $request->setup_name)->first();
        if ($namesetup) {
            return $this->error(['The setup name has already been taken! Try again']);
        }

        try {

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

            return $this->success(['Setup uploaded successfully!'], $main_setup);
        } catch (ValidationException $e) {
            return $this->error($e->errors());
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'id'                  => 'required|exists:main_setups,id',
            'setup_name'          => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $id = $request->id;
        if ($id) {
            $namesetup = MainSetup::where('id', '!=', $id)->where('user_id', auth()->id())->where('name', $request->setup_name)->first();
            if ($namesetup) {
                return $this->error(['The setup name has already been taken! Try again']);
            }

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

            MainSetup::where('user_id', auth()->id())->where('id', $id)->update($data);

            return $this->success(['Setup updated successfully!'], []);
        } else {
            return $this->error(['Please enter valid setup id']);
        }
    }

    public function setupDetail(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $setup = MainSetup::where('id', $id)->first();
            if ($setup) {
                $data = [
                    'setup' => $setup
                ];
                return $this->success(['Setup get successfully!'], $data);
            } else {
                return $this->error(['Please enter valid Setup id']);
            }
        } else {
            return $this->error(['Setup id is required']);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $Setup =  MainSetup::find($id);
            if ($Setup) {
                $Setup->delete();
                return $this->success(['Setup deleted successfully!'], []);
            } else {
                return $this->error(['Please enter valid Setup id']);
            }
        } else {
            return $this->error(['Setup id is required']);
        }
    }
}
