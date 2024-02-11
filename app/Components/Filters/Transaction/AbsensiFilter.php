<?php

namespace App\Components\Filters\Transaction;

use App\Components\Filters\QueryFilters;
use Carbon\Carbon;

class AbsensiFilter extends QueryFilters
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

    public function start_date($v)
    {
        $date = Carbon::parse($v);
        return $this->builder->where('date', '>=', $date->format('Y-m-d'));
    }

    public function end_date($v)
    {
        $date = Carbon::parse($v);
        return $this->builder->where('date', '<=', $date->format('Y-m-d'));
    }
}
