<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SubscriptionResource;
use App\Models\Api\Plan;
use App\Models\Api\Subscription;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'method' => 'required|string|in:credit_card,cash',
        ]);
        $validatedData['user_id'] = auth()->id();

        $existingSubscription = Subscription::where('user_id', $validatedData['user_id'])
            ->where('status', 'active')
            ->first();

        if ($existingSubscription) {
            return $this->error(
                "",
                'User already has an active subscription.',
                400
            );
        }

        $plan = Plan::findOrFail($validatedData['plan_id']);

        $validatedData['status'] = 'active';
        $validatedData['amount'] = $plan->price;
        $validatedData['start_date'] = now();
        $validatedData['end_date'] = now()->addDays($plan->duration_in_days);

        $subscription = Subscription::create($validatedData);

        return $this->success(
            new SubscriptionResource($subscription),
            'Subscribed successfully!',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        if ($subscription->user_id != auth()->id()) {
            return $this->error("", "Unauthorized user!", 401);
        }
        return $this->success(
            new SubscriptionResource($subscription)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        if ($subscription->user_id != auth()->id()) {
            return $this->error("", "Unauthorized user!", 401);
        }
        $subscription->update([
            'status' => 'expired',
        ]);

        return $this->success(
            "",
            'Subscription cancelled successfully!',
            200
        );
    }
}
