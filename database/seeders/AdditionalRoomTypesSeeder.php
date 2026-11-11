<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class AdditionalRoomTypesSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            ['Garden Suite', 'A peaceful suite with a private garden view.', 110, 2, '501', 5],
            ['Junior Suite', 'An airy suite with a separate seating area.', 125, 2, '502', 5],
            ['Family Room', 'A spacious room for a comfortable family stay.', 95, 4, '503', 5],
            ['Executive Room', 'A refined room with extra space to work and unwind.', 135, 2, '504', 5],
            ['Ocean View', 'A bright room with a beautiful ocean view.', 160, 2, '601', 6],
            ['Penthouse Suite', 'A premium top-floor suite with generous living space.', 250, 4, '602', 6],
            ['Honeymoon Suite', 'A private suite designed for a special getaway.', 190, 2, '603', 6],
            ['Accessible Room', 'An accessible room with thoughtful comfort and space.', 70, 2, '604', 6],
        ];

        foreach ($roomTypes as [$name, $description, $price, $capacity, $roomNumber, $floor]) {
            $roomType = RoomType::firstOrCreate(
                ['type_name' => $name],
                [
                    'description' => $description,
                    'price_per_night' => $price,
                    'capacity' => $capacity,
                ],
            );

            Room::firstOrCreate(
                ['room_number' => $roomNumber],
                [
                    'floor' => $floor,
                    'status' => 'available',
                    'room_type_id' => $roomType->room_type_id,
                    'description' => $description,
                ],
            );
        }
    }
}
