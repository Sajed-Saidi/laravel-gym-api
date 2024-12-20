<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $table = "bookings";

    protected $fillable = [
        'user_id',
        'training_class_id',
        'booking_date',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }
}
