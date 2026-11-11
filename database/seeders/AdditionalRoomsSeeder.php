<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdditionalRoomsSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['103', 1, 'Single'],
            ['104', 1, 'Single'],
            ['203', 2, 'Double'],
            ['204', 2, 'Double'],
            ['303', 3, 'Deluxe'],
            ['402', 4, 'Suite'],
            ['403', 4, 'Suite'],
        ];
        $roomTypeIds = RoomType::whereIn('type_name', array_unique(array_column($rooms, 2)))
            ->pluck('room_type_id', 'type_name');

        foreach ($rooms as [$number, $floor, $type]) {
            $roomTypeId = $roomTypeIds->get($type);
            if ($roomTypeId === null) {
                throw new RuntimeException("Cannot add room {$number}: room type {$type} does not exist.");
            }

            Room::firstOrCreate(
                ['room_number' => $number],
                [
                    'floor' => $floor,
                    'status' => 'available',
                    'room_type_id' => $roomTypeId,
                    'description' => 'Additional '.$type.' room.',
                ],
            );
        }
    }
}
