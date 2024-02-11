<?php

namespace App\Components\Filters\ACL;

use App\Components\Filters\QueryFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function role_id($v)
    {
        return $this->builder->where('users.role_id',$v);
    }

    public function not_self($id)
    {
        return $this->builder->where('users.id', '!=', $id);
    }

    public function except_master_admin($val)
    {
        if ($val) {
            return $this->builder->where('users.role_id', '!=', '1');
        }
    }
}
