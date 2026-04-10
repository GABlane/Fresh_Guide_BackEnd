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
                // LEFT COLUMN ROOMS
                ['name' => 'Kitchen Lab', 'code' => 'KITCHEN_LAB', 'type' => 'lab', 'description' => 'Culinary and kitchen laboratory for hospitality management students.'],
                ['name' => 'Bartender Lab', 'code' => 'BARTENDER_LAB', 'type' => 'lab', 'description' => 'Bartending and beverage laboratory for hospitality courses.'],
                ['name' => 'Lecture Room 108', 'code' => '108', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'Lecture Room 105', 'code' => '105', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'Lecture Room 104', 'code' => '104', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],
                ['name' => 'IT Center', 'code' => 'IT_CENTER', 'type' => 'office', 'description' => 'Information Technology support and services center.'],
                ['name' => 'Guidance Office', 'code' => 'GUIDANCE', 'type' => 'office', 'description' => 'Student counseling and guidance services.'],

                // RIGHT COLUMN ROOMS
                ['name' => 'PWD CR', 'code' => 'PWD_CR', 'type' => 'other', 'description' => 'Person with Disability comfort room.'],
                ['name' => 'Lecture Room 109', 'code' => '109', 'type' => 'classroom', 'description' => 'Large lecture room for major classes.'],
                ['name' => 'Faculty Room', 'code' => 'FACULTY', 'type' => 'office', 'description' => 'Faculty lounge and office area.'],
                ['name' => 'Human Resource Office', 'code' => 'HR_OFFICE', 'type' => 'office', 'description' => 'Human resources and personnel management office.'],
                ['name' => 'Finance and Accounting', 'code' => 'FINANCE', 'type' => 'office', 'description' => 'Finance and accounting department office.'],
                ['name' => 'Photography Laboratory', 'code' => 'PHOTO_LAB', 'type' => 'lab', 'description' => 'Photography and multimedia laboratory.'],
                ['name' => 'Criminology Department', 'code' => 'CRIMINOLOGY', 'type' => 'office', 'description' => 'Criminology department office and faculty area.'],
                ['name' => 'Lecture Room 101', 'code' => '101', 'type' => 'classroom', 'description' => 'General classroom for lectures.'],

                // CAMPUS AREA ROOMS (kept from original)
                [
                    'name' => 'Campus Court',
                    'code' => 'COURT',
                    'type' => 'other',
                    'description' => 'Facilities: Covered court, seating, and sound support.\nHours: Mon-Sat 7:00 AM - 8:00 PM.\nBooking: Coordinate with Student Affairs for reservations.\nNotes: Multi-purpose events and sports area.',
                    'location' => 'Campus Center',
                ],
                [
                    'name' => 'Main Entrance',
                    'code' => 'ENT',
                    'type' => 'other',
                    'description' => 'Campus Info: Main access point to UCC campus.\nDirectory: Refer to posted building map near guard desk.\nEmergency: Security assistance available at all times.\nNotes: Recommended starting point for directions.',
                    'location' => 'Campus South Gate',
                ],
                [
                    'name' => 'Main Exit',
                    'code' => 'EXIT',
                    'type' => 'other',
                    'description' => 'Campus Info: Primary outbound route to city road.\nEmergency: Use this path for safe evacuation when advised.\nNotes: Recommended endpoint for departure guidance.',
                    'location' => 'Campus South Gate',
                ],
            ],
            2 => [
                // LEFT COLUMN ROOMS
                ['name' => 'Lecture Room 211', 'code' => 'MAIN-2-LR211', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 209', 'code' => 'MAIN-2-LR209', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 207', 'code' => 'MAIN-2-LR207', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 205', 'code' => 'MAIN-2-LR205', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 203', 'code' => 'MAIN-2-LR203', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 201', 'code' => 'MAIN-2-LR201', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],

                // RIGHT COLUMN ROOMS
                ['name' => 'Lecture Room 212', 'code' => 'MAIN-2-LR212', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 210', 'code' => 'MAIN-2-LR210', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 208', 'code' => 'MAIN-2-LR208', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 206', 'code' => 'MAIN-2-LR206', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 204', 'code' => 'MAIN-2-LR204', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 202', 'code' => 'MAIN-2-LR202', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
            ],
            3 => [
                // LEFT COLUMN ROOMS
                ['name' => 'CBA Coordinators Office', 'code' => 'MAIN-3-CBA-COORD', 'type' => 'office', 'description' => 'College of Business Administration coordinators office.'],
                ['name' => 'CBA Dean\'s Office', 'code' => 'MAIN-3-CBA-DEAN', 'type' => 'office', 'description' => 'Office of the Dean of the College of Business Administration.'],
                ['name' => 'Lecture Room 310', 'code' => 'MAIN-3-LR310', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 308', 'code' => 'MAIN-3-LR308', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Office of the CLAS Program Coordinators', 'code' => 'MAIN-3-CLAS-COORD', 'type' => 'office', 'description' => 'College of Liberal Arts and Sciences program coordinators office.'],
                ['name' => 'Sound Engineering Laboratory', 'code' => 'MAIN-3-SOUND-LAB', 'type' => 'lab', 'description' => 'Sound engineering and audio production laboratory.'],
                ['name' => 'Lecture Room 304', 'code' => 'MAIN-3-LR304', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 302', 'code' => 'MAIN-3-LR302', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],

                // RIGHT COLUMN ROOMS
                ['name' => 'Office of Student Affairs and Services', 'code' => 'MAIN-3-STUDENT-AFFAIRS', 'type' => 'office', 'description' => 'Student affairs and services office.'],
                ['name' => 'MIS Data Center', 'code' => 'MAIN-3-MIS-DATA', 'type' => 'lab', 'description' => 'Management Information Systems data center.'],
                ['name' => 'Computer Studies Department', 'code' => 'MAIN-3-CS-DEPT', 'type' => 'office', 'description' => 'Computer Studies department office.'],
                ['name' => 'Multimedia Room', 'code' => 'MAIN-3-MULTIMEDIA', 'type' => 'lab', 'description' => 'Multimedia production and editing room.'],
                ['name' => 'Lab Tech Room', 'code' => 'MAIN-3-LABTECH', 'type' => 'lab', 'description' => 'Laboratory technology support room.'],
                ['name' => 'Computer Laboratory 1', 'code' => 'MAIN-3-COMPLAB1', 'type' => 'lab', 'description' => 'Computer laboratory with workstations.'],
                ['name' => 'Computer Laboratory 2', 'code' => 'MAIN-3-COMPLAB2', 'type' => 'lab', 'description' => 'Computer laboratory with workstations.'],
                ['name' => 'Computer Laboratory 3', 'code' => 'MAIN-3-COMPLAB3', 'type' => 'lab', 'description' => 'Computer laboratory with workstations.'],
            ],
            4 => [
                // LEFT COLUMN ROOMS
                ['name' => 'Lecture Room 411', 'code' => 'MAIN-4-LR411', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Early Childhood Simulation Room', 'code' => 'MAIN-4-EARLY-CHILD', 'type' => 'lab', 'description' => 'Early childhood education simulation laboratory.'],
                ['name' => 'Lecture Room 408', 'code' => 'MAIN-4-LR408', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Physics Laboratory', 'code' => 'MAIN-4-PHYSICS-LAB', 'type' => 'lab', 'description' => 'Physics laboratory with scientific equipment.'],
                ['name' => 'Lecture Room 404', 'code' => 'MAIN-4-LR404', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'Lecture Room 402', 'code' => 'MAIN-4-LR402', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],

                // RIGHT COLUMN ROOMS
                ['name' => 'COE Council Office', 'code' => 'MAIN-4-COE-COUNCIL', 'type' => 'office', 'description' => 'College of Education council office.'],
                ['name' => 'Educational Technology Laboratory', 'code' => 'MAIN-4-EDTECH-LAB', 'type' => 'lab', 'description' => 'Educational technology and multimedia laboratory.'],
                ['name' => 'Biology Laboratory', 'code' => 'MAIN-4-BIO-LAB', 'type' => 'lab', 'description' => 'Biology laboratory with scientific equipment.'],
                ['name' => 'Chemistry Laboratory', 'code' => 'MAIN-4-CHEM-LAB', 'type' => 'lab', 'description' => 'Chemistry laboratory with scientific equipment.'],
                ['name' => 'College of Law Office', 'code' => 'MAIN-4-LAW-OFFICE', 'type' => 'office', 'description' => 'College of Law administrative office.'],
                ['name' => 'Lecture Room 401', 'code' => 'MAIN-4-LR401', 'type' => 'classroom', 'description' => 'General lecture room for classes.'],
                ['name' => 'College of Education', 'code' => 'MAIN-4-COE', 'type' => 'office', 'description' => 'College of Education administrative office.'],
            ],
            5 => [
                ['name' => 'V-48', 'code' => 'MAIN-5-V48', 'type' => 'classroom', 'description' => 'General classroom for lectures.', 'location' => 'Left Wing'],
                ['name' => 'V-46', 'code' => 'MAIN-5-V46', 'type' => 'classroom', 'description' => 'General classroom for lectures.', 'location' => 'Left Wing'],
                ['name' => 'Industrial Arts Laboratory', 'code' => 'MAIN-5-INDARTS', 'type' => 'lab', 'description' => 'Industrial arts laboratory.', 'location' => 'Left Wing'],
                ['name' => 'Speech Laboratory', 'code' => 'MAIN-5-SPEECH', 'type' => 'lab', 'description' => 'Speech and communication laboratory.', 'location' => 'Right Wing'],
                ['name' => 'Craft / Sewing Laboratory', 'code' => 'MAIN-5-SEWING', 'type' => 'lab', 'description' => 'Craft and sewing laboratory.', 'location' => 'Right Wing'],
                ['name' => 'Office', 'code' => 'MAIN-5-OFFICE', 'type' => 'office', 'description' => 'Office room on the right wing.', 'location' => 'Right Wing'],
                ['name' => 'Auditorium', 'code' => 'MAIN-5-AUDIT', 'type' => 'other', 'description' => 'Main auditorium.', 'location' => 'Center Hall'],
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
            'name' => 'Main Entrance',
            'code' => 'ENT',
            'description' => 'Campus entrance origin point.',
        ]);

        Origin::create([
            'name' => 'Main Exit',
            'code' => 'EXIT',
            'description' => 'Campus exit origin point.',
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
                // 'name' => 'Main Gate to Registrar',
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
            'note' => 'UCC main building seed with 5 floors, room image support, and campus area rooms.',
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
