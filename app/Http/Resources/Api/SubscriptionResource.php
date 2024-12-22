<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'plan' => $this->plan,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'amount' => $this->amount,
            'method' => $this->method,
            'paymentDate' => $this->payment_date,
            'status' => $this->status,
            'paymentStatus' => $this->payment_status,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];
    }
}
