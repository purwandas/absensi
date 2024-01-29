<?php

namespace App\Http\Controllers\Master;

use App\Components\Filters\BasicFilter;
use App\Components\Helpers\ResponseHelper;
use App\Http\Controllers\BaseController;
use App\Models\Master\Device;
use Illuminate\Http\Request;

class DeviceController extends BaseController
{
    protected 
        $model           = Device::class,
        $filter          = BasicFilter::class,
        $with_relation   = ['createdBy', 'updatedBy'],
        $views           = 'master.device',
        $edit_url        = 'device/detail',
        $delete_url      = 'device/delete',
        // $return_model = ['save'],
        $raw_columns     = [];
}
