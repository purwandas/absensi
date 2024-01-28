<?php

namespace App\Models\Master;

use App\Models\BaseModel;

class HariLibur extends BaseModel
{
    protected $appends = ['select2_label'];

    public static function rule(){
        return [
            'date'        => 'required',
            'description' => 'required',
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
                    <li class="breadcrumb-item">'.$this->date.'</li>
                    <li class="breadcrumb-item">'.$this->description.'</li>
                </ol>';
    }
}
