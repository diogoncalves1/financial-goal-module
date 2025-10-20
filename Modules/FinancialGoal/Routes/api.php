<?php

use Illuminate\Support\Facades\Route;
use Modules\FinancialGoal\Http\Controllers\Api\FinancialGoalContributionController;
use Modules\FinancialGoal\Http\Controllers\Api\FinancialGoalController;
use Modules\FinancialGoal\Http\Controllers\Api\FinancialGoalUserController;

Route::group([
    // 'middleware' => ['auth:sanctum'],
    'prefix' => 'v1'
], function () {
    Route::group([
        'as' => 'financial-goals.',
        'prefix' => 'financial-goals'
    ], function () {
        Route::get('/{id}/users', [FinancialGoalUserController::class, 'users'])->name('users');
        Route::post('/{id}/invite', [FinancialGoalUserController::class, 'invite'])->name('invite');
        Route::post('/{id}/accept', [FinancialGoalUserController::class, 'accept'])->name('accept');
        Route::post('/{id}/revoke', [FinancialGoalUserController::class, 'revokeInvite'])->name('revoke-invite');
        Route::post('/{id}/revoke-user', [FinancialGoalUserController::class, 'revokeUser'])->name('revoke-user');
        Route::put('/{id}/user-role', [FinancialGoalUserController::class, 'updateUserRole'])->name('update-user-role');
        Route::delete('/{id}/leave', [FinancialGoalUserController::class, 'leave'])->name('leave');
    });
    Route::apiResource('financial-goals', FinancialGoalController::class)->names('financial-goal');
    Route::apiResource('financial-goals-contributions', FinancialGoalContributionController::class)->names('financial-goal-contribution');
});
