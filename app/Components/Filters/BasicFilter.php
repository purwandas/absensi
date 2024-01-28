<?php

namespace App\Components\Filters;

use App\Components\Filters\QueryFilters;

class UserFilter extends QueryFilters
{
    public function q($v)
    {
        return $this->builder->where('users.name','LIKE',"%$v%");
    }

    public function name($v)
    {
    	return $this->builder->where('users.name','LIKE',"%$v%");
    }
}
