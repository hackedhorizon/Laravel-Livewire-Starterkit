<?php

namespace Database\Seeders;

use App\Models\FailedLoginAttempt;
use Illuminate\Database\Seeder;

class FailedLoginAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FailedLoginAttempt::factory(10)->create();
    }
}
