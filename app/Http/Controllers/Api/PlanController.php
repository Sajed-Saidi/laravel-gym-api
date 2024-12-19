<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlanResource;
use App\Models\Api\Plan;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $plans = Plan::where('status', 'active')->get();
        return $this->success(
            [
                'plans' => PlanResource::collection(new PlanResource($plans)),
            ]
        );
    }

    public function show(Plan $plan)
    {
        return $this->success([
            'plan' => new PlanResource($plan)
        ]);
    }
}
