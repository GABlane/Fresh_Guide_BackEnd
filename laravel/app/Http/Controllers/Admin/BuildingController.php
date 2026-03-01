<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount('floors')->latest()->get();
        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code',
            'description' => 'nullable|string',
        ]);

        Building::create($data);

        return redirect()->route('admin.buildings.index')->with('success', 'Building created.');
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'description' => 'nullable|string',
        ]);

        $building->update($data);

        return redirect()->route('admin.buildings.index')->with('success', 'Building updated.');
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return redirect()->route('admin.buildings.index')->with('success', 'Building deleted.');
    }
}
