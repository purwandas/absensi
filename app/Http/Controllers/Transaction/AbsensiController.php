<?php

namespace App\Http\Controllers\Transaction;

use App\Components\Filters\Transaction\AbsensiFilter;
use App\Components\Helpers\ResponseHelper;
use App\Http\Controllers\BaseController;
use App\Models\Transaction\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use hisorange\BrowserDetect\Parser as Browser;

class AbsensiController extends BaseController
{
    protected 
        $model           = Absensi::class,
        $filter          = AbsensiFilter::class,
        $with_relation   = ['user', 'createdBy', 'updatedBy'],
        $views           = 'transaction.absensi',
        $edit_url        = 'absensi/detail',
        $delete_url      = 'absensi/delete',
        // $return_model = ['save'],
        $raw_columns     = [];

    public function save(Request $request)
    {
        $date = Carbon::now();

        $request['user_id']  = \Auth::user()->id;
        $request['date']  = $date->format("Y-m-d");
        $request['time']  = $date->format("H:i:s");
        $request['ip_address']  = $_SERVER['REMOTE_ADDR'];
        $request['mac_address'] = getMacAddress();
        $request['device_info'] = json_encode([
            'deviceType'   => Browser::deviceType(),
            'deviceFamily' => Browser::deviceFamily(),
            'deviceModel'  => Browser::deviceModel(),
        ]);

        if(@$request->id){
            $model = $this->model::findOrFail(@$request->id);

            $request->validate(array_merge(method_exists($model, 'ruleOnUpdate') ? $model->ruleOnUpdate() : [], method_exists($this, 'ruleOnUpdate') ? $this->ruleOnUpdate() : []));

            $model->update($request->except(['id']));
        }else{
            $model = new $this->model;

            $request->validate(array_merge(method_exists($model, 'rule') ? $model->rule() : [], method_exists($this, 'rule') ? $this->rule() : []));

            $model = $this->model::create($request->all());
        }

        return ResponseHelper::sendResponse($model, 'Data Has Been Saved!');
    }
}
