<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;

class AppController
{
    protected function allowedAction($permission): void
    {
        if (auth()->user()->hasPermission($permission)) {
            throw new AuthorizationException('This action is unauthorized');
        }
    }
}
