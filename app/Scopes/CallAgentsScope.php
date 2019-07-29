<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CallAgentsScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(strtolower(auth()->user()->role->name) == 'call_center_manager' && isset(auth()->user()->call_center_id)) {
            $builder->where('created_by', '=', auth()->user()->id);
        } else {
            $builder;
        }
    }
}
