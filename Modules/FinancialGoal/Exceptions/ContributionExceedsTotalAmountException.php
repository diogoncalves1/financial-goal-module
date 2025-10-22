<?php

namespace Modules\FinancialGoal\Exceptions;

use Exception;

class ContributionExceedsTotalAmountException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct(float $contribution = 0, float $totalAmount = 0)
    {
        $this->message = __('financialgoal::exceptions.financial-goal-contributions.contributionExceedsTotalAmountException', ['contribution' => $contribution, 'totalAmount' => $totalAmount]);
    }
}
