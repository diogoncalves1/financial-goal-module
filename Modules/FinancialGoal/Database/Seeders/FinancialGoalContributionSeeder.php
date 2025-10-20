<?php

namespace Modules\FinancialGoal\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FinancialGoal\Entities\FinancialGoalContribution;

class FinancialGoalContributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FinancialGoalContribution::factory(3)->create();
    }
}
