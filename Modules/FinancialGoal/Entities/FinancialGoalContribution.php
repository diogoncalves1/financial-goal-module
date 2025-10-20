<?php

namespace Modules\FinancialGoal\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Accounts\Entities\Transaction;
use Modules\User\Entities\User;

class FinancialGoalContribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['financial_goal_id', 'user_id', 'transaction_id', 'amount', 'date', 'description', 'status'];

    protected static function newFactory()
    {
        return \Modules\FinancialGoal\Database\Factories\FinancialGoalContributionFactory::new();
    }

    public function financialGoal()
    {
        return $this->belongsTo(FinancialGoal::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
