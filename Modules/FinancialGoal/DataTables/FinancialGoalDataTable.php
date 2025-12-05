<?php
namespace Modules\FinancialGoal\DataTables;

use Modules\Accounts\Core\Helpers;
use Modules\FinancialGoal\Core\Helpers as CoreHelpers;
use Modules\FinancialGoal\Entities\FinancialGoalView;
use Yajra\DataTables\Services\DataTable;

class FinancialGoalDataTable extends DataTable
{

    public function dataTable($query)
    {
        $request = request();

        $user = $request->user();

        return datatables()
            ->eloquent($query)
            ->addColumn('totalAmountFormated', fn(FinancialGoalView $financialGoal) => Helpers::formatMoneyWithSymbolAndCurrency($financialGoal->totalAmount, $financialGoal->currencyCode, $financialGoal->currencySymbol))
            ->addColumn('contributedAmountFormated', fn(FinancialGoalView $financialGoal) => Helpers::formatMoneyWithSymbolAndCurrency($financialGoal->contributedAmount, $financialGoal->currencyCode, $financialGoal->currencySymbol))
            ->addColumn('percentageCompeted', fn(FinancialGoalView $financialGoal) => CoreHelpers::percentage($financialGoal->totalAmount, $financialGoal->contributedAmount))
            ->addColumn('priorityTranslated', fn(FinancialGoalView $financialGoal) => __('financialgoal::attributes.financial-goals.priority.' . $financialGoal->priority))
            ->addColumn('statusTranslated', fn(FinancialGoalView $financialGoal) => __('financialgoal::attributes.financial-goals.status.' . $financialGoal->status))
            ->addColumn('actions', function (FinancialGoalView $financialGoal) use ($user) {
                $sharedRole = $financialGoal->userSharedRole($financialGoal, $user->id);

                $canEdit    = $sharedRole->hasPermission('updateFinancialGoal');
                $canDestroy = $sharedRole->hasPermission('destroyFinancialGoal');

                return ['edit' => $canEdit, 'destroy' => $canDestroy];
            })
            ->removeColumn('userIds')
            ->removeColumn('currencyId')
            ->removeColumn('currencyCode')
            ->removeColumn('canceledAt')
            ->removeColumn('completedAt');

    }

    public function query(FinancialGoalView $model)
    {
        $request = request();

        $user = $request->user();

        $query = $model->newQuery()
            ->whereRaw("FIND_IN_SET(?, REPLACE(userIds, ' ', ''))", [$user->id]);

        if ($request->has('status')) {
            $query->active($request->get('status'));
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere("totalAmount", 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
