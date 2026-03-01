<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::latest()->get();
        return view('admin.origins.index', compact('origins'));
    }

    public function create()
    {
        return view('admin.origins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:origins,code',
            'description' => 'nullable|string',
        ]);

        Origin::create($data);

        return redirect()->route('admin.origins.index')->with('success', 'Origin created.');
    }

    public function edit(Origin $origin)
    {
        return view('admin.origins.edit', compact('origin'));
    }

    public function update(Request $request, Origin $origin)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:origins,code,' . $origin->id,
            'description' => 'nullable|string',
        ]);

        $origin->update($data);

        return redirect()->route('admin.origins.index')->with('success', 'Origin updated.');
    }

    public function destroy(Origin $origin)
    {
        $origin->delete();
        return redirect()->route('admin.origins.index')->with('success', 'Origin deleted.');
    }
}
