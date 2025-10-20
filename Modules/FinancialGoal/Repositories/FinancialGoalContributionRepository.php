<?php

namespace Modules\FinancialGoal\Repositories;

use App\Repositories\RepositoryApiInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\FinancialGoal\Entities\FinancialGoalContribution;

class FinancialGoalContributionRepository implements RepositoryApiInterface
{
    public FinancialGoalRepository $financialGoalRepository;

    public function __construct(FinancialGoalRepository $financialGoalRepository)
    {
        $this->financialGoalRepository = $financialGoalRepository;
    }

    public function all()
    {
        return FinancialGoalContribution::all();
    }

    public function allUser(Request $request) {}

    public function store(Request $request)
    {
        // TODO : Finish that

        return DB::transaction(function () use ($request) {
            $input = $request->all();

            $financialGoalContribution = FinancialGoalContribution::create($input);

            if ($financialGoalContribution->status == 'completed') $this->adjustFinancialGoalContributedAmount($financialGoalContribution);

            return $financialGoalContribution;
        });
    }

    public function update(Request $request, string $id) {}

    public function destroy(?Request $request = null, string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $financialGoalContribution = $this->show($id);
                $financialGoalContribution->delete();
            });
        } catch (\Exception $e) {
            throw new $e;
        }
    }

    public function show(string $id)
    {
        return FinancialGoalContribution::findOrFail($id);
    }


    // Private methods
    private function adjustFinancialGoalContributedAmount(FinancialGoalContribution $contribution): void
    {
        $financialGoal = $contribution->financialGoal;

        $financialGoal->contributed_amount += $contribution->amount;

        $financialGoal->save();
    }
    private function updateFinancialGoalContributedAmount(FinancialGoalContribution $contribution, float $difference): void
    {
        $financialGoal = $contribution->financialGoal;

        $financialGoal->contributed_amount -= $difference;

        $financialGoal->save();
    }
    private function reverseFinancialGoalContributedAmount(FinancialGoalContribution $contribution): void
    {
        $financialGoal = $contribution->financialGoal;

        $financialGoal->contributed_amount -= $contribution->amount;

        $financialGoal->save();
    }
}
