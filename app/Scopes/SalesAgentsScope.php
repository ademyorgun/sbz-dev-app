<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SalesAgentsScope implements Scope {
    /**
     * Apply the scope to a given Eloquent query builder.
     * Scope a query to only include the appointments assigned 
     * for the sales agent
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (strtolower(auth()->user()->role->name) == 'sales_agent') {
            return $builder
                ->where('sales_agent_id', auth()->user()->id);
        } else {
            return $builder;
        }
    }
}