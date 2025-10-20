<?php

namespace Modules\FinancialGoal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ApiResponder\Traits\RespondsWithApi;
use Modules\FinancialGoal\Http\Requests\FinancialGoalRequest;
use Modules\FinancialGoal\Http\Resources\ErrorResponseResource;
use Modules\FinancialGoal\Http\Resources\FinancialGoalCollection;
use Modules\FinancialGoal\Http\Resources\FinancialGoalResource;
use Modules\FinancialGoal\Http\Resources\SuccessResponseResource;
use Modules\FinancialGoal\Repositories\FinancialGoalRepository;

class FinancialGoalController extends Controller
{
    use RespondsWithApi;

    protected FinancialGoalRepository $repository;

    public function __construct(FinancialGoalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResource
     */
    public function index(Request $request): JsonResource
    {
        try {
            $financialGoals = $this->repository->allUser($request);

            return new FinancialGoalCollection($financialGoals);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param FinancialGoalRequest $request
     * @return JsonResponse
     */
    public function store(FinancialGoalRequest $request): JsonResponse
    {
        try {
            $financialGoal = $this->repository->store($request);

            return $this->ok(new FinancialGoalResource($financialGoal), __('financialgoals::messages.financial-goals.store'));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $id
     * @return FinancialGoalResource
     * @throws AuthorizationException
     */
    public function show(Request $request, string $id): JsonResource
    {
        try {
            if (!$this->repository->hasPermission($request->user(), $id, 'viewFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoal = $this->repository->show($id);

            return new FinancialGoalResource($financialGoal);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param FinancialGoalRequest $request
     * @param string $id
     * @return FinancialGoalResource
     * @throws AuthorizationException
     */
    public function update(Request $request, $id): JsonResource
    {
        try {
            if (!$this->repository->hasPermission($request->user(), $id, 'updateFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoal = $this->repository->update($request, $id);

            return new FinancialGoalResource($financialGoal)->additional(['message' => __('')]);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, string $id): JsonResource
    {
        try {
            if (!$this->repository->hasPermission($request->user(), $id, 'deleteFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $this->repository->destroy(id: $id);

            return new SuccessResponseResource(['message' => __('alerts.destroySuccess'), 'code' => 200]);
        } catch (\Exception $e) {
            return new ErrorResponseResource(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}
