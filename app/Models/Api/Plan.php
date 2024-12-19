<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = "plans";

    protected $fillable = [
        'name',
        'price',
        'duration_in_days',
        'features',
        'status'
    ];
}
