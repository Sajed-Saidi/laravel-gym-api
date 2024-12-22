<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TrainerResource;
use App\Models\Api\Trainer;
use App\Traits\ApiResponseTrait;

class TrainerController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->success(
            TrainerResource::collection(Trainer::all())
        );
    }
}
