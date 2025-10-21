<?php

namespace Modules\FinancialGoal\DataTables;

use Modules\FinancialGoal\Entities\FinancialGoal;
use Modules\FinancialGoal\Repositories\FinancialGoalRepository;
use Modules\User\Entities\User;
use Yajra\DataTables\Services\DataTable;

class FinancialGoalDataTable  extends DataTable
{
    protected $repository;

    public function __construct(FinancialGoalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function dataTable($query)
    {
        $request = request();

        $user = $request->user();

        return datatables()
            ->eloquent($query)
            ->editColumn('status', fn($row) => __('financialgoal::attributes.financial-goals.status.' . $row->status))
            ->addColumn('totalAmount', fn($row) => $row->total_amount)
            ->addColumn('currency', fn($row) => $row->currency->symbol)
            ->addColumn('startDate', fn($row) => $row->start_date)
            ->addColumn('dueDate', fn($row) => $row->due_date)
            ->addColumn('completedAt', fn($row) => $row->completed_at)
            ->addColumn('contributedAmount', fn($row) => $row->contributed_at)
            ->addColumn('actions', function ($row) use ($user) {

                $canEdit = $this->repository->hasPermission($user, $row->id, 'updateFinancialGoal');
                $canDestroy = $this->repository->hasPermission($user, $row->id, 'destroyFinancialGoal');

                return ['edit' => $canEdit, 'destroy' => $canDestroy];
            })
            ->removeColumn('total_amount')
            ->removeColumn('currency_id')
            ->removeColumn('start_date')
            ->removeColumn('completed_at')
            ->removeColumn('due_date')
            ->removeColumn('contributed_amount');
    }

    public function query(FinancialGoal $model)
    {
        $request = request();

        $user = $request->user();

        return $model->newQuery()->select('financial_goals.*')
            ->join('financial_goal_users', 'financial_goals.id', '=', 'financial_goal_users.financial_goal_id')
            ->join('shared_roles', 'financial_goal_users.shared_role_id', '=', 'shared_roles.id')
            ->where('financial_goal_users.status', 'accepted')
            ->where('financial_goal_users.user_id', $user->id);
    }
}
