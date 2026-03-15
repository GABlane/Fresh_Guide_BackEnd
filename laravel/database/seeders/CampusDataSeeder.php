<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\CampusRoute;
use App\Models\DataVersion;
use App\Models\Facility;
use App\Models\Floor;
use App\Models\Origin;
use App\Models\Room;
use App\Models\RouteStep;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CampusDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@freshguide.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        $main = Building::create([
            'name' => 'UCC South Main Building',
            'code' => 'MAIN',
            'description' => 'Primary academic building for administration and classes.',
        ]);

        Building::create([
            'name' => 'Library',
            'code' => 'LIB',
            'description' => 'Main campus library building.',
        ]);

        Building::create([
            'name' => 'Registrar Annex',
            'code' => 'REG',
            'description' => 'Registrar support offices.',
        ]);

        $floors = [];
        for ($floorNumber = 1; $floorNumber <= 5; $floorNumber++) {
            $floors[$floorNumber] = Floor::create([
                'building_id' => $main->id,
                'number' => $floorNumber,
                'name' => $floorNumber . $this->ordinalSuffix($floorNumber) . ' Floor',
            ]);
        }

        $facilities = [
            'wifi' => Facility::create(['name' => 'WiFi', 'icon' => 'ic_wifi']),
            'ac' => Facility::create(['name' => 'Air Conditioning', 'icon' => 'ic_ac']),
            'projector' => Facility::create(['name' => 'Projector', 'icon' => 'ic_projector']),
            'computer' => Facility::create(['name' => 'Computers', 'icon' => 'ic_computer']),
            'whiteboard' => Facility::create(['name' => 'Whiteboard', 'icon' => 'ic_whiteboard']),
            'sound' => Facility::create(['name' => 'Sound System', 'icon' => 'ic_speaker']),
        ];

        $roomsByFloor = [
            1 => [
                ['name' => 'Main Lobby', 'code' => 'MAIN-1-LOBBY', 'type' => 'other', 'description' => 'Main reception and waiting area.'],
                ['name' => 'Registrar Office', 'code' => 'MAIN-1-REG', 'type' => 'office', 'description' => 'Handles enrollment and student records.'],
                ['name' => 'Student Services', 'code' => 'MAIN-1-STUSVC', 'type' => 'office', 'description' => 'Student support and assistance desk.'],
                ['name' => 'Finance Office', 'code' => 'MAIN-1-FIN', 'type' => 'office', 'description' => 'Tuition and payment concerns.'],
                ['name' => 'Dean\'s Office', 'code' => 'MAIN-1-DEAN', 'type' => 'office', 'description' => 'Administrative office for the dean.'],
            ],
            2 => [
                ['name' => 'Classroom 201', 'code' => 'MAIN-2-CR201', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'Classroom 202', 'code' => 'MAIN-2-CR202', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'Classroom 203', 'code' => 'MAIN-2-CR203', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'Lecture Room 221', 'code' => 'MAIN-2-LR221', 'type' => 'classroom', 'description' => 'Larger room for major classes.'],
            ],
            3 => [
                ['name' => 'Computer Laboratory', 'code' => 'MAIN-3-COMPLAB', 'type' => 'lab', 'description' => 'Main computer laboratory.'],
                ['name' => 'MIS Department', 'code' => 'MAIN-3-MIS', 'type' => 'office', 'description' => 'Management Information Systems office.'],
                ['name' => 'Computer Science Department', 'code' => 'MAIN-3-CS', 'type' => 'office', 'description' => 'Computer Science department office.'],
                ['name' => 'Server Room', 'code' => 'MAIN-3-SERVER', 'type' => 'other', 'description' => 'Network and server equipment room.'],
            ],
            4 => [
                ['name' => 'LabTech Facility', 'code' => 'MAIN-4-LABTECH', 'type' => 'lab', 'description' => 'Laboratory technology workspace.'],
                ['name' => 'Law Faculty Office', 'code' => 'MAIN-4-LAW', 'type' => 'office', 'description' => 'Law faculty office area.'],
                ['name' => 'Law Classroom 401', 'code' => 'MAIN-4-LAW401', 'type' => 'classroom', 'description' => 'Law classroom.'],
            ],
            5 => [
                ['name' => 'Auditorium Court', 'code' => 'MAIN-5-AUDIT', 'type' => 'other', 'description' => 'Auditorium at the side near the entrance.', 'location' => 'Side A'],
                ['name' => 'Psychology Classroom 501', 'code' => 'MAIN-5-PSY501', 'type' => 'classroom', 'description' => 'Psychology classroom in the far wing.', 'location' => 'Side B'],
                ['name' => 'Psychology Classroom 502', 'code' => 'MAIN-5-PSY502', 'type' => 'classroom', 'description' => 'Psychology classroom in the far wing.', 'location' => 'Side B'],
            ],
        ];

        foreach ($roomsByFloor as $floorNumber => $rooms) {
            foreach ($rooms as $roomData) {
                $room = Room::create([
                    'floor_id' => $floors[$floorNumber]->id,
                    'name' => $roomData['name'],
                    'code' => $roomData['code'],
                    'type' => $roomData['type'],
                    'description' => $roomData['description'],
                    'location' => $roomData['location'] ?? null,
                    'image_url' => null,
                ]);

                $facilityIds = [$facilities['wifi']->id];
                if (in_array($roomData['type'], ['classroom', 'lab'], true)) {
                    $facilityIds[] = $facilities['ac']->id;
                    $facilityIds[] = $facilities['projector']->id;
                }
                if ($roomData['type'] === 'lab') {
                    $facilityIds[] = $facilities['computer']->id;
                }
                if ($roomData['code'] === 'MAIN-5-AUDIT') {
                    $facilityIds[] = $facilities['sound']->id;
                }
                if ($roomData['type'] === 'classroom') {
                    $facilityIds[] = $facilities['whiteboard']->id;
                }

                $room->facilities()->sync(array_values(array_unique($facilityIds)));
            }
        }

        $mainGate = Origin::create([
            'name' => 'Main Gate',
            'code' => 'GATE',
            'description' => 'Primary campus entrance.',
        ]);

        Origin::create([
            'name' => 'Main Lobby',
            'code' => 'LOBBY',
            'description' => 'Main building lobby near reception.',
        ]);

        Origin::create([
            'name' => 'Stairwell A',
            'code' => 'STA',
            'description' => 'Stairway near the entrance side.',
        ]);

        Origin::create([
            'name' => 'Stairwell B',
            'code' => 'STB',
            'description' => 'Stairway near the far wing.',
        ]);

        $registrar = Room::where('code', 'MAIN-1-REG')->first();
        if ($registrar) {
            $route = CampusRoute::create([
                'origin_id' => $mainGate->id,
                'destination_room_id' => $registrar->id,
                'name' => 'Main Gate to Registrar',
                'description' => 'Direct walking route from main gate to registrar office.',
            ]);

            $steps = [
                [1, 'Enter through Main Gate and walk straight to the Main Building entrance.', 'straight', 'Main Building'],
                [2, 'Proceed to the lobby and keep right.', 'right', 'Main Lobby'],
                [3, 'Registrar Office is on your right side.', 'right', 'Registrar Office'],
            ];

            foreach ($steps as [$order, $instruction, $direction, $landmark]) {
                RouteStep::create([
                    'route_id' => $route->id,
                    'order' => $order,
                    'instruction' => $instruction,
                    'direction' => $direction,
                    'landmark' => $landmark,
                ]);
            }
        }

        DataVersion::create([
            'version' => 1,
            'note' => 'UCC main building seed with 5 floors and room image support.',
            'published_by' => $admin->id,
            'published_at' => now(),
        ]);
    }

    private function ordinalSuffix(int $number): string
    {
        if ($number >= 11 && $number <= 13) {
            return 'th';
        }

        return match ($number % 10) {
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
            default => 'th',
        };
    }
}
