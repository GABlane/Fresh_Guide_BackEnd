<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataVersion;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    public function publish(Request $request)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $latest     = DataVersion::latest('published_at')->first();
        $newVersion = ($latest?->version ?? 0) + 1;

        DataVersion::create([
            'version'      => $newVersion,
            'note'         => $request->input('note'),
            'published_by' => auth()->id(),
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', "Version {$newVersion} published successfully.");
    }
}
