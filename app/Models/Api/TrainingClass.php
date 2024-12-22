<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingClass extends Model
{
    protected $table = "training_classes";

    protected $fillable = [
        'trainer_id',
        'name',
        'description',
        'schedule',
        'category',
        'image'
    ];

    protected $casts = [
        'schedule' => 'json',
    ];
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function bookings(): HasMany
    {
        return  $this->hasMany(Booking::class);
    }
}
