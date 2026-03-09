<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    public function publish(Request $request): JsonResponse
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $latest     = DataVersion::latest('published_at')->first();
        $newVersion = ($latest?->version ?? 0) + 1;

        $dataVersion = DataVersion::create([
            'version'      => $newVersion,
            'note'         => $request->input('note'),
            'published_by' => $request->user()->id,
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'version'      => $dataVersion->version,
                'note'         => $dataVersion->note,
                'published_at' => $dataVersion->published_at,
            ],
            'error' => null,
        ]);
    }
}
