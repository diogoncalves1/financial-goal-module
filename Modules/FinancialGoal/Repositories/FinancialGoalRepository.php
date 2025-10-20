<?php

namespace Modules\FinancialGoal\Repositories;

use App\Repositories\RepositoryApiInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\FinancialGoal\Entities\FinancialGoal;
use Modules\FinancialGoal\Entities\FinancialGoalUser;
use Modules\SharedRoles\Entities\SharedRole;
use Modules\SharedRoles\Repositories\SharedRoleRepository;
use Modules\User\Entities\User;

class FinancialGoalRepository implements RepositoryApiInterface
{
    protected SharedRoleRepository $sharedRoleRepository;

    public function __construct(SharedRoleRepository $sharedRoleRepository)
    {
        $this->sharedRoleRepository = $sharedRoleRepository;
    }

    public function all()
    {
        return FinancialGoal::all();
    }

    public function allUser(Request $request) {}

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // $user = $request->user();

            $input = $request->all();

            $financialGoal = FinancialGoal::create($input);

            $inputUser = [
                'user_id' => 2, //$user->id,
                'financial_goal_id' => $financialGoal->id,
                'shared_role_id' => SharedRole::where('code', 'creator')->first()->id,
                'status' => 'accepted',
                'accepted_at' => Carbon::now()
            ];

            FinancialGoalUser::create($inputUser);

            return $financialGoal;
        });
    }

    public function update(Request $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $financialGoal = $this->show($id);

                $input = $request->all();

                $financialGoal->update($input);

                return $financialGoal;
            });
        } catch (\Exception $e) {
            throw new $e;
        }
    }

    public function destroy(?Request $request = null, string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $financialGoal = $this->show($id);
            });
        } catch (\Exception $e) {
            throw new $e;
        }
    }

    public function show(string $id)
    {
        return FinancialGoal::findOrFail($id);
    }

    public function userSharedRole(FinancialGoal $financialGoal, string $userId)
    {
        $user = $financialGoal->users()
            ->where('user_id', $userId)
            ->where('status', 'accepted')
            ->join('shared_roles', 'financial_goal_users.shared_role_id', '=', 'shared_roles.id')
            ->first();

        if ($user)
            return $this->sharedRoleRepository->show($user?->pivot->shared_role_id);
        return null;
    }

    public function hasPermission(User $user, string $id, string $permission)
    {
        $financialGoal = $this->show($id);

        $userFG = $financialGoal->users()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->join('shared_roles', 'financial_goal_users.shared_role_id', '=', 'shared_roles.id')
            ->first();

        if ($userFG)
            return $this->sharedRoleRepository->show($userFG?->pivot->shared_role_id)->hasPermission($permission);
        return null;
    }
}
