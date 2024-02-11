<?php

namespace App\Models\Master;

use App\Models\ACL\User;
use App\Models\BaseModel;

class Device extends BaseModel
{
    protected $appends = ['select2_label'];

    public static function rule(){
        return [
            'name'        => 'required',
            'mac_address' => 'required',
            'user_id'     => 'required',
        ];
    }

    public static function ruleOnUpdate(){
        $rule = self::rule();

        return $rule;
    }

    public function getSelect2LabelAttribute()
    {
        // return '<div class="badge badge-dark me-2">'.$this->permission_key.'</div>'.(@$this->group_label ? $this->group_label.' - ' : '').$this->action_label;
        // return (@$this->group_label ? $this->group_label.' - ' : '').$this->action_label;

        return '<ol class="breadcrumb breadcrumb-dot fs-6 fw-semibold">
                    <li class="breadcrumb-item">'.$this->name.'</li>
                    <li class="breadcrumb-item">'.$this->mac_address.'</li>
                </ol>';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
