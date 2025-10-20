<?php

namespace Modules\Accounts\Exceptions;

use Exception;

class InvalidTransactionDateException extends Exception
{
    protected $message;
    protected $code = 500;

    public function __construct()
    {
        parent::__construct(__('exceptions.invalidTransactionDateException'), $this->code);
    }
}
