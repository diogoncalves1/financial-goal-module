<?php

namespace Modules\FinancialGoal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FinancialGoal\Entities\FinancialGoal;
use Modules\FinancialGoal\Entities\FinancialGoalContribution;
use Modules\User\Entities\User;

class FinancialGoalContributionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = FinancialGoalContribution::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {

        return [
            'financial_goal_id' => FinancialGoal::pluck('id')->random(),
            'user_id' => User::pluck('id')->random(),
            'transaction_id' => null,
            'amount' => $this->faker->randomFloat(2),
            'date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'completed'])
        ];
    }
}
