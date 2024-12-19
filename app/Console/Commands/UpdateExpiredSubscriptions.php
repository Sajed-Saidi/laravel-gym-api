<?php

namespace App\Console\Commands;

use App\Models\Api\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscriptions status to expired if end_date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->update(['status' => 'expired']);

        $this->info("Updated {$expiredSubscriptions} subscriptions to expired.");
    }
}
