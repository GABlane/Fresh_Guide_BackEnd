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
        // ── Admin user ────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@freshguide.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // ── Buildings ─────────────────────────────────────────────────────────
        $main = Building::create([
            'name'        => 'UCC South Main Building',
            'code'        => 'MAIN',
            'description' => 'Primary academic building',
        ]);
        Building::create([
            'name'        => 'Court',
            'code'        => 'COURT',
            'description' => 'Open court area at the center of campus',
        ]);
        Building::create([
            'name'        => 'Library',
            'code'        => 'LIB',
            'description' => 'Main campus library',
        ]);
        Building::create([
            'name'        => 'Registrar',
            'code'        => 'REG',
            'description' => 'Student records and enrollment office',
        ]);
        Building::create([
            'name'        => 'Entrance',
            'code'        => 'ENT',
            'description' => 'Main campus entrance',
        ]);
        Building::create([
            'name'        => 'Exit',
            'code'        => 'EXIT',
            'description' => 'Main campus exit',
        ]);

        // ── Floors (Ground Floor per building) ───────────────────────────────
        foreach (Building::all() as $b) {
            Floor::create([
                'building_id' => $b->id,
                'number'      => 0,
                'name'        => 'Ground Floor',
            ]);
        }

        // ── Main Building Floors 1–4 ─────────────────────────────────────────
        $mainFloors = [];
        for ($floorNumber = 1; $floorNumber <= 4; $floorNumber++) {
            $mainFloors[$floorNumber] = Floor::create([
                'building_id' => $main->id,
                'number'      => $floorNumber,
                'name'        => 'Floor ' . $floorNumber,
            ]);
        }

        // ── Rooms (10 per floor, placeholder names) ──────────────────────────
        foreach ($mainFloors as $floorNumber => $floor) {
            for ($slot = 1; $slot <= 10; $slot++) {
                $roomNumber = (int) sprintf('%d%02d', $floorNumber, $slot);
                Room::create([
                    'floor_id'    => $floor->id,
                    'name'        => 'Room ' . $roomNumber,
                    'code'        => sprintf('M%d-%02d', $floorNumber, $slot),
                    'type'        => 'classroom',
                    'description' => 'Main Building Floor ' . $floorNumber,
                ]);
            }
        }

        // ── Facilities ────────────────────────────────────────────────────────
        $wifi      = Facility::create(['name' => 'WiFi',             'icon' => 'ic_wifi']);
        $ac        = Facility::create(['name' => 'Air Conditioning', 'icon' => 'ic_ac']);
        $projector = Facility::create(['name' => 'Projector',        'icon' => 'ic_projector']);

        // Facilities left unattached for now; admin can assign later.

        // ── Origins ───────────────────────────────────────────────────────────
        $mainGate  = Origin::create(['name' => 'Main Gate',  'code' => 'GATE',
            'description' => 'Primary campus entrance.']);
        Origin::create(['name' => 'Registrar', 'code' => 'REG',
            'description' => 'Student records and enrollment office.']);
        Origin::create(['name' => 'Library',   'code' => 'LIB',
            'description' => 'Main campus library.']);

        // ── Route: Main Gate → Room 101 ───────────────────────────────────────
        $room101 = Room::where('code', 'M1-01')->first();
        if ($room101) {
            $route = CampusRoute::create([
                'origin_id'           => $mainGate->id,
                'destination_room_id' => $room101->id,
                'name'                => 'Main Gate to Room 101',
                'description'         => 'Shortest path from the main entrance to Room 101.',
            ]);

            $steps = [
                [1, 'Enter through the Main Gate and proceed straight.',      'straight', null],
                [2, 'Walk past the open court on your left.',                  'straight', 'Court'],
                [3, 'Turn left at the Registrar building.',                    'left',     'Registrar'],
                [4, 'Continue straight through the corridor.',                 'straight', null],
                [5, 'Room 101 is the second door on your right.',              'right',    null],
            ];

            foreach ($steps as [$order, $instruction, $direction, $landmark]) {
                RouteStep::create([
                    'route_id'    => $route->id,
                    'order'       => $order,
                    'instruction' => $instruction,
                    'direction'   => $direction,
                    'landmark'    => $landmark,
                ]);
            }
        }

        // ── Initial data version ──────────────────────────────────────────────
        DataVersion::create([
            'version'      => 1,
            'note'         => 'Initial seed data.',
            'published_by' => User::first()->id,
            'published_at' => now(),
        ]);
    }
}
