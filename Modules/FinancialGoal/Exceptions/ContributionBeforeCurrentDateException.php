<?php

namespace Modules\FinancialGoal\Exceptions;

use Exception;

class ContributionBeforeCurrentDateException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        $this->message = __('financialgoal::exceptions.financial-goal-contributions.contributionBeforeCurrentDateException');
    }
}
