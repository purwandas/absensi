<?php

namespace App\Components\Filters;

use App\Components\Filters\QueryFilters;

class BasicFilter extends QueryFilters
{
    public function q($v)
    {
        return $this->builder->where('name','LIKE',"%$v%");
    }

    public function name($v)
    {
    	return $this->builder->where('name','LIKE',"%$v%");
    }
}
