<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SettingResource;
use App\Models\Api\Setting;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $settings = Setting::first();

        if (!$settings) {
            $settings = [
                'id' => 1,
                'website_name' => 'GYM',
                'email' => 'gym@gmail.com',
                'phone' => '+961 71 595 345',
                'address' => 'GYM Beirut, Lebanon',
                'meta_description' => 'gym gym gym',
                'meta_keywords' => 'gymgymgym',
                'logo' => '01JFMQZJF9QJFV40PGWYA8XVD2.png',
                'facebook_url' => 'https://facebook.com',
                'instagram_url' => 'https://instagram.com',
                'created_at' => '2024-12-21 13:54:46',
                'updated_at' => '2024-12-21 13:54:46'
            ];
        }

        return $this->success(
            new SettingResource($settings),
            "Settings Fetched Successfully!"
        );
    }
}
