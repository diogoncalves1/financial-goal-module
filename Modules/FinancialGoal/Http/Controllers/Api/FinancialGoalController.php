<?php

namespace Modules\FinancialGoal\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\FinancialGoal\DataTables\FinancialGoalDataTable;
use Modules\FinancialGoal\Http\Requests\FinancialGoalRequest;
use Modules\FinancialGoal\Http\Resources\ErrorResponseResource;
use Modules\FinancialGoal\Http\Resources\FinancialGoalCollection;
use Modules\FinancialGoal\Http\Resources\FinancialGoalResource;
use Modules\FinancialGoal\Http\Resources\SuccessResponseResource;
use Modules\FinancialGoal\Repositories\FinancialGoalRepository;

class FinancialGoalController extends ApiController
{
    protected FinancialGoalRepository $repository;

    public function __construct(FinancialGoalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param FinancialGoalDataTable $dataTable
     * @return JsonResponse
     */
    public function index(Request $request, FinancialGoalDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
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

            return $this->ok(new FinancialGoalResource($financialGoal), __('financialgoal::messages.financial-goals.store', ['name' => $financialGoal->name]));
        } catch (\Exception $e) {
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
            if (!$this->repository->hasPermission($request->user(), $id, 'viewFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoal = $this->repository->show($id);

            return $this->ok(new FinancialGoalResource($financialGoal));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param FinancialGoalRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(FinancialGoalRequest $request, string $id): JsonResponse
    {
        try {
            if (!$this->repository->hasPermission($request->user(), $id, 'updateFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoal = $this->repository->update($request, $id);

            return $this->ok(new FinancialGoalResource($financialGoal), __('financialgoal::messages.financial-goals.update', ['name' => $financialGoal->name]));
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
            if (!$this->repository->hasPermission($request->user(), $id, 'deleteFinancialGoal'))
                throw new AuthorizationException(__('exceptions.denied'), 403);

            $financialGoal = $this->repository->destroy(id: $id);

            return $this->ok(message: __('financialgoal::messages.financial-goals.destroy', ['name' => $financialGoal->name]));
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), $e, $e->getCode());
        }
    }
}
