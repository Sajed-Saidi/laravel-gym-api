<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingClassResource extends JsonResource
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
            'trainer' => new TrainerResource($this->whenLoaded('trainer')),
            'name' => $this->name,
            'description' => $this->description,
            'schedule' => $this->schedule,
            'image' => \env("APP_URL", "http://127.0.0.1:8000") . "/storage/" .  $this->image,
            'category' => $this->category,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
