<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";

    protected $fillable = [
        'website_name',
        'email',
        'phone',
        'address',
        'meta_description',
        'meta_keywords',
        'logo',
        'facebook_url',
        'instagram_url'
    ];
}
