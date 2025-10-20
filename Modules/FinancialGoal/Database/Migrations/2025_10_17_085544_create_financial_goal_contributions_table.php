<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_goal_contributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_goal_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->decimal('amount', 15);
            $table->date('date');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('financial_goal_id')->references('id')->on('financial_goals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');

            $permissions = [
                ['name' => 'Ver Contribuicao Meta Financeira', 'code' => 'viewFinancialGoalContribution', 'category' => 'Contribuicoes Metas Financeiras'],
                ['name' => 'Adicionar Contribuicoes da Meta Financeira', 'code' => 'storeFinancialGoalContribution', 'category' => 'Contribuicoes Metas Financeiras'],
                ['name' => 'Editar Contribuições da Meta Financeira', 'code' => 'updateFinancialGoalContribution', 'category' => 'Contribuicoes Metas Financeiras'],
                ['name' => 'Confirmar Contribuíções Agendadas', 'code' => 'confirmScheduledFinancialGoalContributions', 'category' => 'Contribuicoes Metas Financeiras'],
                ['name' => 'Apagar Contribuicoes da Meta Financeira', 'code' => 'destroyFinancialGoalContribution', 'category' => 'Contribuicoes Metas Financeiras'],
            ];

            foreach ($permissions as $permission) {
                $id = DB::table('shared_permissions')->insertGetId($permission);
                $permissionRole[] = ['shared_permission_id' => $id, 'shared_role_id' => 1];
            }

            DB::table('shared_permission_roles')->insert($permissionRole);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_goal_contributions');

        $permissions = ['viewFinancialGoalContribution', 'storeFinancialGoalContribution', 'updateFinancialGoalContribution', 'confirmScheduledFinancialGoalContributions', 'destroyFinancialGoalContribution'];

        foreach ($permissions as $permission) {
            DB::table('shared_permissions')->where('code', $permission)->delete();
        }
    }
};
