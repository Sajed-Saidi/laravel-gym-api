<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('subscriptions:update-expired', function () {
    $expiredSubscriptions = \App\Models\Api\Subscription::where('status', 'active')
        ->where('end_date', '<', \Carbon\Carbon::now())
        ->update(['status' => 'expired']);

    $this->info("Updated {$expiredSubscriptions} subscriptions to expired.");
})->hourly();
