<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TrainingClassResource;
use App\Models\Api\TrainingClass;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TrainingClassController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->success(
            [
                'trainingClasses' => TrainingClassResource::collection(TrainingClass::all())
            ],
        );
    }
}
