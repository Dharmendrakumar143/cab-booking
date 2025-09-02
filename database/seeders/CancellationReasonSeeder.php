<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['reason' => 'Change of plans (e.g., you no longer need the ride)', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Wrong pick-up/drop-off details', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Payment issues', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'No longer need the ride', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Safety concerns', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Weather-related', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Safety concerns', 'created_at' => now(), 'updated_at' => now()],
            ['reason' => 'Other reason', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($reasons as $reason) {
            // Check if the reason already exists
            if (!DB::table('cancellation_reasons')->where('reason', $reason['reason'])->exists()) {
                DB::table('cancellation_reasons')->insert($reason);
            }
        }
    }
}
