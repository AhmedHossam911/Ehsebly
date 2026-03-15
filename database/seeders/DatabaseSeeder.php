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

        // 2. Generate Egyptian Users Pool
        $egyptianNames = [
            'Mahmoud Ali', 'Karim Hassan', 'Omar Tarek', 'Mostafa Gamal', 'Youssef Ahmed',
            'Hassan Saeed', 'Amr Diab', 'Tarek Ziad', 'Ziad Ibrahim', 'Mohamed Salah',
            'Sara Magdy', 'Yasmin Farouk', 'Nour El-Din', 'Salma Yasser', 'Farah Essam',
            'Aya Khaled', 'Habiba Sayed', 'Mariam Wael', 'Rana Nabil', 'Dina Samir'
        ];

        $users = [$ahmed];

        foreach ($egyptianNames as $name) {
            // Generate a professional looking avatar based on name
            $encodedName = urlencode($name);
            $bgColors = ['10b981', '6366f1', 'f43f5e', 'eab308', '8b5cf6', '0ea5e9'];
            $randomBg = $bgColors[array_rand($bgColors)];
            
            $users[] = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'password' => $password,
                'avatar_url' => "https://ui-avatars.com/api/?name={$encodedName}&background={$randomBg}&color=fff&size=256&bold=true",
            ]);
        }

        // 3. Establish Friendships (Ahmed is friends with everyone, and some random inter-friendships)
        $this->command->info('Establishing Egyptian Friendships...');
        foreach ($users as $user) {
            if ($user->id !== $ahmed->id) {
                // Ahmed adds them
                Friend::create([
                    'user_id' => $ahmed->id,
                    'friend_id' => $user->id,
                    'status' => 'accepted'
                ]);
                // They add Ahmed back (mutual relationship depending on your schema, assuming bidirectional here or single record)
                 Friend::create([
                    'user_id' => $user->id,
                    'friend_id' => $ahmed->id,
                    'status' => 'accepted'
                ]);
            }
        }

        // 4. Create Historic Egyptian Events
        $this->command->info('Creating Historic Egyptian Events...');
        
        $pastEvents = [
            ['name' => 'سفرية الساحل 2023', 'description' => 'مصاريف فيلا وبنزين وأكل الساحل الصيف اللي فات', 'date' => Carbon::now()->subMonths(8)],
            ['name' => 'خروجة دهب - رأس السنة', 'description' => 'تذاكر وفندق وكامبينج', 'date' => Carbon::now()->subMonths(3)],
            ['name' => 'فطار رمضان - التجمع', 'description' => 'عزومة الشباب في خيمة التجمع الخامس', 'date' => Carbon::now()->subYear(1)->addMonths(1)],
            ['name' => 'حجز خماسي - النادي', 'description' => 'ماتش الكورة الإسبوعي', 'date' => Carbon::now()->subWeeks(2)],
            ['name' => 'هدية عيد ميلاد مروان', 'description' => 'جمعية الهدية والتورتة', 'date' => Carbon::now()->subMonths(1)],
            ['name' => 'شاليه السخنة - الويك إند', 'description' => 'يومين في العين السخنة', 'date' => Carbon::now()->subDays(10)],
        ];

        foreach ($pastEvents as $eventData) {
            $event = Event::create([
                'creator_id' => $ahmed->id,
                'name' => $eventData['name'],
                'date' => tap($eventData['date']->copy(), function($d) {}) // Clone to prevent mutation issues
            ]);

            // Add Ahmed and 3-6 random friends to the event
            $participants = collect($users)->where('id', '!=', $ahmed->id)->random(rand(3, 6))->push($ahmed);
            
            foreach ($participants as $participant) {
                EventParticipant::create([
                    'event_id' => $event->id,
                    'user_id' => $participant->id
                ]);
            }

            $eventParticipants = EventParticipant::where('event_id', $event->id)->get();

            // 5. Generate Expenses within the event
            $expenseNames = ['غداء مشاوي', 'بنزين وكارتة', 'سوبر ماركت', 'مشروبات وقهوة', 'إيجار', 'تذاكر الباص', 'عشاء سي فود', 'سهرة 카페'];
            $numExpenses = rand(3, 8);

            for ($i = 0; $i < $numExpenses; $i++) {
                $amount = rand(300, 5000);
                $expenseDate = $eventData['date']->copy()->addHours(rand(1, 48));
                
                $expense = Expense::create([
                    'event_id' => $event->id,
                    'description' => $expenseNames[array_rand($expenseNames)],
                    'total_amount' => $amount,
                    'date' => $expenseDate
                ]);

                // Determine who paid
                $payerPart = $eventParticipants->random();
                ExpensePayer::create([
                    'expense_id' => $expense->id,
                    'participant_id' => $payerPart->id,
                    'amount' => $amount
                ]);

                // Split equally among all participants
                $splitAmount = $amount / $eventParticipants->count();
                foreach ($eventParticipants as $ep) {
                    ExpenseSplit::create([
                        'expense_id' => $expense->id,
                        'participant_id' => $ep->id,
                        'amount' => $splitAmount
                    ]);
                }
            }

            // 6. Generate Some Random Historic Settlements for realism (Debts being paid off)
            if (rand(1, 100) > 40) { // 60% chance event has some settlements
                $payerPart = $eventParticipants->where('user_id', '!=', $ahmed->id)->random();
                $ahmedPart = $eventParticipants->where('user_id', $ahmed->id)->first();
                if ($ahmedPart && $payerPart) {
                    Settlement::create([
                        'from_participant_id' => $payerPart->id,
                        'to_participant_id' => $ahmedPart->id,
                        'event_id' => $event->id,
                        'amount' => rand(100, 500),
                        'status' => 'completed'
                    ]);
                }
            }
        }
        
        $this->command->info('Egyptian Database Seeder completed successfully!');
    }
}
