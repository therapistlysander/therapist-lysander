<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingAvailability;

class BookingAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $defaultSlots = ['09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'];

        // Monday through Friday active, Saturday and Sunday inactive
        for ($day = 0; $day <= 6; $day++) {
            BookingAvailability::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'time_slots' => $day <= 4 ? $defaultSlots : [],
                    'is_active'  => $day <= 4, // Mon-Fri active
                ]
            );
        }
    }
}
