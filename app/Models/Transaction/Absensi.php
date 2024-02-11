<?php

namespace App\Models\Transaction;

use App\Models\ACL\User;
use App\Models\BaseModel;

class Absensi extends BaseModel
{
    public static function rule(){
        return [
            'date'        => 'required',
            'time'        => 'required',
            'latitude'    => 'required',
            'longitude'   => 'required',
            'ip_address'  => 'nullable',
            'mac_address' => 'nullable',
            'device_info' => 'nullable',
        ];
    }

    public static function ruleOnUpdate(){
        $rule = self::rule();

        return $rule;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
