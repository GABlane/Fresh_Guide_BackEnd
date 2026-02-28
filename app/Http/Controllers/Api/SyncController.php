<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\CampusRoute;
use App\Models\DataVersion;
use App\Models\Facility;
use App\Models\Origin;
use Illuminate\Http\JsonResponse;

class SyncController extends Controller
{
    /**
     * GET /api/sync/version
     * Returns the latest published version number only.
     */
    public function version(): JsonResponse
    {
        $latest = DataVersion::latest('published_at')->first();

        return response()->json([
            'version'      => $latest?->version ?? 0,
            'published_at' => $latest?->published_at,
        ]);
    }

    /**
     * GET /api/sync/bootstrap
     * Returns the full campus data payload for offline sync.
     */
    public function bootstrap(): JsonResponse
    {
        $latest = DataVersion::latest('published_at')->first();

        return response()->json([
            'version'      => $latest?->version ?? 0,
            'published_at' => $latest?->published_at,
            'data'         => [
                'buildings'  => Building::with('floors.rooms.facilities')->get(),
                'facilities' => Facility::all(),
                'origins'    => Origin::all(),
                'routes'     => CampusRoute::with(['origin', 'destinationRoom', 'steps'])->get(),
            ],
        ]);
    }
}
