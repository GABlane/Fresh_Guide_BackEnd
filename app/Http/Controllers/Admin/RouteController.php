<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusRoute;
use App\Models\Origin;
use App\Models\Room;
use App\Models\RouteStep;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = CampusRoute::with(['origin', 'destinationRoom'])->latest()->get();
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        $origins = Origin::all();
        $rooms   = Room::with('floor.building')->get();
        return view('admin.routes.create', compact('origins', 'rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'origin_id'           => 'required|exists:origins,id',
            'destination_room_id' => 'required|exists:rooms,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'steps'               => 'nullable|array',
            'steps.*.order'       => 'required|integer|min:1',
            'steps.*.instruction' => 'required|string',
            'steps.*.direction'   => 'nullable|string|in:straight,left,right,up,down',
            'steps.*.landmark'    => 'nullable|string|max:255',
        ]);

        $route = CampusRoute::create($data);

        foreach ($data['steps'] ?? [] as $step) {
            $route->steps()->create($step);
        }

        return redirect()->route('admin.routes.index')->with('success', 'Route created.');
    }

    public function edit(CampusRoute $route)
    {
        $origins = Origin::all();
        $rooms   = Room::with('floor.building')->get();
        $route->load('steps');
        return view('admin.routes.edit', compact('route', 'origins', 'rooms'));
    }

    public function update(Request $request, CampusRoute $route)
    {
        $data = $request->validate([
            'origin_id'           => 'required|exists:origins,id',
            'destination_room_id' => 'required|exists:rooms,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'steps'               => 'nullable|array',
            'steps.*.order'       => 'required|integer|min:1',
            'steps.*.instruction' => 'required|string',
            'steps.*.direction'   => 'nullable|string|in:straight,left,right,up,down',
            'steps.*.landmark'    => 'nullable|string|max:255',
        ]);

        $route->update($data);

        // Replace steps in bulk
        $route->steps()->delete();
        foreach ($data['steps'] ?? [] as $step) {
            $route->steps()->create($step);
        }

        return redirect()->route('admin.routes.index')->with('success', 'Route updated.');
    }

    public function destroy(CampusRoute $route)
    {
        $route->delete(); // steps cascade via FK
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted.');
    }
}
