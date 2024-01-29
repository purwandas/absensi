<?php

namespace App\Components\Filters\Master;

use App\Components\Filters\QueryFilters;
use Carbon\Carbon;

class HariLiburFilter extends QueryFilters
{
    public function q($v)
    {
        return $this->builder->where('name','LIKE',"%$v%");
    }

    public function name($v)
    {
    	return $this->builder->where('name','LIKE',"%$v%");
    }

    public function month($v)
    {
        $date = Carbon::parse($v);
        return $this->builder->whereMonth('date',$date->month)->whereYear('date',$date->year);
    }
}
