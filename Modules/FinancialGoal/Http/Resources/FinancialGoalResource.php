<?php

namespace Modules\FinancialGoal\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialGoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'totalAmount' => $this->total_amount,
            'currency' => $this->whenLoaded('currency'),
            'users' => $this->whenLoaded('users'),
            'contributions' => $this->whenLoaded('contributions'),
            'contributedAmount' => $this->contributed_amount
        ];
    }
}
