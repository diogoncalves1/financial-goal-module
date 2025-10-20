<?php

namespace Modules\FinancialGoal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\FinancialGoal\Classes\Repositories\FinancialGoalUserRepository;
use Modules\FinancialGoal\Http\Resources\ErrorResponseResource;
use Modules\FinancialGoal\Http\Resources\FinancialGoalUserResource;

class FinancialGoalUserController extends Controller
{
    protected FinancialGoalUserRepository $repository;

    /**
     * Display a listing of the users resource.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function users(Request $request, string $id): JsonResource
    {
        try {

            // $financialGoal = $this->repository->showWithUsers($request, $id);

            // return new FinancialGoalResource($financialGoal);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function invite(Request $request, string $id): JsonResource
    {
        try {
            $financialGoalUser = $this->repository->inviteUser($request, $id);

            return new FinancialGoalUserResource($financialGoalUser);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Accept a newly invite resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function accept(Request $request, string $id): JsonResource
    {
        try {
            $financialGoalUser = $this->repository->acceptInvite($request, $id);

            return new FinancialGoalUserResource($financialGoalUser);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Revoke invite a newly invite resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function revokeInvite(Request $request, string $id): JsonResource
    {
        try {
            $financialGoalUser = $this->repository->revokeInvite($request, $id);

            return new FinancialGoalUserResource($financialGoalUser);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Revoke user a newly invite resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResource
     */
    public function revokeUser(Request $request, string $id) //: JsonResource
    {
        return view('financialgoal::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUserRole(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function leave($id) {}
}
