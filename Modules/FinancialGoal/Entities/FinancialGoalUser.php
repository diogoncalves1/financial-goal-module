<?php

namespace Modules\FinancialGoal\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialGoalUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'shared_role_id', 'financial_goal_id', 'status', 'accepted_at', 'invited_at'];

    protected static function newFactory()
    {
        return \Modules\FinancialGoal\Database\Factories\FinancialGoalUserFactory::new();
    }
}
