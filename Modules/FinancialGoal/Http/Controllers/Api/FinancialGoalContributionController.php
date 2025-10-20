<?php

namespace Modules\FinancialGoal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\ApiResponder\Traits\RespondsWithApi;
use Modules\FinancialGoal\Http\Requests\FinancialGoalContributionRequest;
use Modules\FinancialGoal\Http\Resources\FinancialGoalContributionCollection;
use Modules\FinancialGoal\Http\Resources\FinancialGoalContributionResource;
use Modules\FinancialGoal\Repositories\FinancialGoalContributionRepository;
use Modules\User\Entities\User;

class FinancialGoalContributionController extends Controller
{

    use RespondsWithApi;

    protected FinancialGoalContributionRepository $repository;

    public function __construct(FinancialGoalContributionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $financialGoalContributions = $this->repository->allUser($request);

            return $this->ok(new FinancialGoalContributionCollection($financialGoalContributions), __(''));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param FinancialGoalContributionRequest $request
     * @return JsonResponse
     */
    public function store(FinancialGoalContributionRequest $request): JsonResponse
    {
        try {
            if (!$this->repository->financialGoalRepository->hasPermission(/*$request->user()*/User::find(2), $request->get('financial_goal_id'), 'storeFinancialGoalContribution'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoalContribution = $this->repository->store($request);

            return $this->ok(new FinancialGoalContributionResource($financialGoalContribution), __('financialgoal::messages.financial-goal-contributions.store', ['name' => $financialGoalContribution->financialGoal->name]));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $financialGoalContribution = $this->repository->show($id);

            if (!$this->repository->financialGoalRepository->hasPermission($request->user(), $financialGoalContribution->financial_goal_id, 'viewFinancialGoalContribution'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            return $this->ok(new FinancialGoalContributionResource($financialGoalContribution), __(''));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param FinancialGoalContributionRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(FinancialGoalContributionRequest $request, string $id): JsonResponse
    {
        try {
            if (!$this->repository->financialGoalRepository->hasPermission($request->user(), $this->repository->show($id)->financial_goal_id, 'updateFinancialGoalContribution'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoalContribution = $this->repository->update($request, $id);

            return $this->ok(new FinancialGoalContributionResource($financialGoalContribution), __(''));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            if (!$this->repository->financialGoalRepository->hasPermission($request->user(), $this->repository->show($id)->financial_goal_id, 'destroyFinancialGoalContribution'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $this->repository->destroy(id: $id);

            return $this->ok(message: __(''));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }
}
