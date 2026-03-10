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

        $floor = Floor::where('building_id', $main->id)->first();

        // ── Rooms ─────────────────────────────────────────────────────────────
        $room201 = Room::create([
            'floor_id'    => $floor->id,
            'name'        => 'Room 201',
            'code'        => 'R201',
            'type'        => 'classroom',
            'description' => 'Main lecture room, capacity 40.',
        ]);

        $room305 = Room::create([
            'floor_id'    => $floor->id,
            'name'        => 'Room 305',
            'code'        => 'R305',
            'type'        => 'office',
            'description' => 'Faculty office.',
        ]);

        // ── Facilities ────────────────────────────────────────────────────────
        $wifi      = Facility::create(['name' => 'WiFi',             'icon' => 'ic_wifi']);
        $ac        = Facility::create(['name' => 'Air Conditioning', 'icon' => 'ic_ac']);
        $projector = Facility::create(['name' => 'Projector',        'icon' => 'ic_projector']);

        $room201->facilities()->attach([$wifi->id, $projector->id]);
        $room305->facilities()->attach([$wifi->id, $ac->id]);

        // ── Origins ───────────────────────────────────────────────────────────
        $mainGate  = Origin::create(['name' => 'Main Gate',  'code' => 'GATE',
            'description' => 'Primary campus entrance.']);
        Origin::create(['name' => 'Registrar', 'code' => 'REG',
            'description' => 'Student records and enrollment office.']);
        Origin::create(['name' => 'Library',   'code' => 'LIB',
            'description' => 'Main campus library.']);

        // ── Route: Main Gate → Room 201 ───────────────────────────────────────
        $route = CampusRoute::create([
            'origin_id'           => $mainGate->id,
            'destination_room_id' => $room201->id,
            'name'                => 'Main Gate to Room 201',
            'description'         => 'Shortest path from the main entrance to Room 201.',
        ]);

        $steps = [
            [1, 'Enter through the Main Gate and proceed straight.',      'straight', null],
            [2, 'Walk past the open court on your left.',                  'straight', 'Court'],
            [3, 'Turn left at the Registrar building.',                    'left',     'Registrar'],
            [4, 'Continue straight through the corridor.',                 'straight', null],
            [5, 'Room 201 is the second door on your right.',              'right',    null],
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

        // ── Initial data version ──────────────────────────────────────────────
        DataVersion::create([
            'version'      => 1,
            'note'         => 'Initial seed data.',
            'published_by' => User::first()->id,
            'published_at' => now(),
        ]);
    }
}
