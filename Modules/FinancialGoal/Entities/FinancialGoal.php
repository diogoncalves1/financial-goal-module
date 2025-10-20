<?php

namespace Modules\FinancialGoal\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Currency\Entities\Currency;
use Modules\User\Entities\User;


class FinancialGoal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['currency_id', 'name', 'total_amount', 'contributed_amount', 'start_date', 'due_date', 'status', 'description', 'completed_at'];

    protected static function newFactory()
    {
        return \Modules\FinancialGoal\Database\Factories\FinancialGoalFactory::new();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'financial_goal_users', 'financial_goal_id', 'user_id')
            ->withPivot('shared_role_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function contributions()
    {
        return $this->hasMany(FinancialGoalContribution::class);
    }
}
