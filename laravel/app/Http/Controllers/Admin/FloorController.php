<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::with('building')->latest()->get();
        return view('admin.floors.index', compact('floors'));
    }

    public function create()
    {
        $buildings = Building::all();
        return view('admin.floors.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'number'      => 'required|integer|min:0',
            'name'        => 'required|string|max:255',
        ]);

        Floor::create($data);

        return redirect()->route('admin.floors.index')->with('success', 'Floor created.');
    }

    public function edit(Floor $floor)
    {
        $buildings = Building::all();
        return view('admin.floors.edit', compact('floor', 'buildings'));
    }

    public function update(Request $request, Floor $floor)
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'number'      => 'required|integer|min:0',
            'name'        => 'required|string|max:255',
        ]);

        $floor->update($data);

        return redirect()->route('admin.floors.index')->with('success', 'Floor updated.');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return redirect()->route('admin.floors.index')->with('success', 'Floor deleted.');
    }
}
