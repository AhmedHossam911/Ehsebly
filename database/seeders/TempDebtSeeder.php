<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Settlement;

class TempDebtSeeder extends Seeder
{
    public function run()
    {
        $mainUser = User::first();
        if (!$mainUser) {
            $mainUser = User::factory()->create();
        }

        $friendUser = User::where('id', '!=', $mainUser->id)->first();
        if (!$friendUser) {
            $friendUser = User::factory()->create();
        }

        $event = Event::first() ?? Event::factory()->create(['creator_id' => $mainUser->id]);

        $part1 = EventParticipant::firstOrCreate([
            'event_id' => $event->id, 
            'user_id' => $mainUser->id
        ], ['guest_name' => null]);

        $part2 = EventParticipant::firstOrCreate([
            'event_id' => $event->id, 
            'user_id' => $friendUser->id
        ], ['guest_name' => null]);

        Settlement::create([
            'event_id' => $event->id,
            'from_participant_id' => $part1->id,
            'from_user_id' => $mainUser->id,
            'to_participant_id' => $part2->id,
            'to_user_id' => $friendUser->id,
            'amount' => 150.50,
            'status' => 'pending'
        ]);

        Settlement::create([
            'event_id' => $event->id,
            'from_participant_id' => $part2->id,
            'from_user_id' => $friendUser->id,
            'to_participant_id' => $part1->id,
            'to_user_id' => $mainUser->id,
            'amount' => 75.00,
            'status' => 'pending'
        ]);

        echo "Debts seeded successfully.\n";
    }
}
