<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'websiteName'      => $this->website_name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'metaDescription'  => $this->meta_description,
            'metaKeywords'     => $this->meta_keywords,
            'logo'              => \env("APP_URL", "http://127.0.0.1:8000") . "/storage/" .  $this->logo,
            'facebookUrl'      => $this->facebook_url,
            'instagramUrl'     => $this->instagram_url,
        ];
    }
}
