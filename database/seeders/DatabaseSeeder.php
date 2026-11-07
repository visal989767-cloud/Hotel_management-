<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => '123456']
        );

        $types = [
            ['type_name' => 'Single', 'description' => 'Cozy room with one single bed.', 'price_per_night' => 30, 'capacity' => 1],
            ['type_name' => 'Double', 'description' => 'Comfortable room with a double bed.', 'price_per_night' => 50, 'capacity' => 2],
            ['type_name' => 'Deluxe', 'description' => 'Spacious room with city view.', 'price_per_night' => 80, 'capacity' => 3],
            ['type_name' => 'Suite', 'description' => 'Luxury suite with living area.', 'price_per_night' => 150, 'capacity' => 4],
        ];
        foreach ($types as $t) {
            RoomType::updateOrCreate(['type_name' => $t['type_name']], $t);
        }

        $typeId = RoomType::pluck('room_type_id', 'type_name');

        $rooms = [
            ['101', 1, 'Single', 'available'],
            ['102', 1, 'Single', 'available'],
            ['201', 2, 'Double', 'available'],
            ['202', 2, 'Double', 'maintenance'],
            ['301', 3, 'Deluxe', 'available'],
            ['302', 3, 'Deluxe', 'available'],
            ['401', 4, 'Suite', 'available'],
        ];
        foreach ($rooms as [$number, $floor, $type, $status]) {
            Room::updateOrCreate(['room_number' => $number], [
                'floor' => $floor,
                'status' => $status,
                'room_type_id' => $typeId[$type],
            ]);
        }

        $guests = [
            ['Kong Sopheak', 'kongsopheak@gmail.com', '012345678', 'Male'],
            ['Ly Chhailin', 'lin@gmail.com', '098765432', 'Female'],
            ['Pov Nita', 'povnita@gmail.com', '011223344', 'Female'],
            ['Leng Makara', 'lengmakara@gmail.com', '077889900', 'Male'],
        ];
        foreach ($guests as [$name, $email, $phone, $gender]) {
            Guest::updateOrCreate(['email' => $email], [
                'guest_name' => $name, 'phone' => $phone, 'gender' => $gender,
            ]);
        }

        if (Booking::count() === 0) {
            $sample = [
                ['kongsopheak@gmail.com', '101', '-5 days', '-2 days', 'checked_out'],
                ['lin@gmail.com', '201', '-1 day', '+2 days', 'checked_in'],
                ['povnita@gmail.com', '301', '+3 days', '+6 days', 'booked'],
                ['lengmakara@gmail.com', '401', '+10 days', '+12 days', 'cancelled'],
            ];
            foreach ($sample as [$email, $roomNo, $in, $out, $status]) {
                $room = Room::with('roomType')->where('room_number', $roomNo)->first();
                $checkIn = now()->modify($in)->startOfDay();
                $checkOut = now()->modify($out)->startOfDay();
                $nights = max(1, (int) abs($checkIn->diffInDays($checkOut)));

                Booking::create([
                    'guest_id' => Guest::where('email', $email)->value('guest_id'),
                    'room_id' => $room->room_id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'total_price' => $nights * $room->roomType->price_per_night,
                ]);

                if ($status === 'checked_in') {
                    $room->update(['status' => 'occupied']);
                }
            }
        }
    }
}
