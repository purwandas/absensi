<?php

namespace App\Http\Controllers\Master;

use App\Components\Filters\Master\HariLiburFilter;
use App\Components\Helpers\ResponseHelper;
use App\Http\Controllers\BaseController;
use App\Models\Master\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends BaseController
{
    protected 
        $model           = HariLibur::class,
        $filter          = HariLiburFilter::class,
        $with_relation   = ['createdBy', 'updatedBy'],
        $views           = 'master.hari-libur',
        $edit_url        = 'hari-libur/detail',
        $delete_url      = 'hari-libur/delete',
        // $return_model = ['save'],
        $raw_columns     = [];
}
