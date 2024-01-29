<?php

namespace App\Http\Controllers\Master;

use App\Components\Filters\BasicFilter;
use App\Components\Helpers\ResponseHelper;
use App\Http\Controllers\BaseController;
use App\Models\Master\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends BaseController
{
    protected 
        $model           = Jabatan::class,
        $filter          = BasicFilter::class,
        $with_relation   = ['createdBy', 'updatedBy'],
        $views           = 'master.jabatan',
        $edit_url        = 'jabatan/detail',
        $delete_url      = 'jabatan/delete',
        // $return_model = ['save'],
        $raw_columns     = [];
}
