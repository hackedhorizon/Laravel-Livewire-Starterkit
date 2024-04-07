<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified users who haven\'t verified their email within the specified timeframe';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $expirationDate = now()->subDays(config('auth.verification.expire', 7));

        $unverifiedUsers = User::whereNull('email_verified_at')
            ->where('created_at', '<=', $expirationDate)
            ->get();

        foreach ($unverifiedUsers as $user) {
            $user->delete();
        }

        $this->info(count($unverifiedUsers).' unverified users deleted successfully.');
    }
}
