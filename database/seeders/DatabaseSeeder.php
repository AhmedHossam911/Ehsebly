<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Friend;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Expense;
use App\Models\ExpensePayer;
use App\Models\ExpenseSplit;
use App\Models\Settlement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Create the Primary User representing the App Owner
        $ahmed = User::create([
            'name' => 'Ahmed Hossam',
            'email' => 'ahmed@ehsebly.com',
            'password' => $password,
            'avatar_url' => 'https://ui-avatars.com/api/?name=Ahmed+Hossam&background=10b981&color=fff&size=256',
        ]);

        
        $this->command->info('Egyptian Database Seeder completed successfully!');
    }
}
