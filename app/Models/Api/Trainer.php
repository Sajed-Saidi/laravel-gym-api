<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    protected $table = "trainers";

    protected $fillable = [
        'user_id',
        'specialties',
        'rating',
        'image',
        'additional_info'
    ];

    protected $casts = [
        'additional_info' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainingClasses(): HasMany
    {
        return $this->hasMany(TrainingClass::class);
    }
}
