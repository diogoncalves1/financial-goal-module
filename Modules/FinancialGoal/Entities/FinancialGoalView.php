<?php
namespace Modules\FinancialGoal\Entities;

class FinancialGoalView extends FinancialGoal
{
    protected $table = 'financial_goal_view';

    protected $casts = [
        'totalAmount'       => 'float',
        'contributedAmount' => 'float',
    ];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
