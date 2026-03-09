<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\JsonResponse;

class OriginController extends Controller
{
    public function index(): JsonResponse
    {
        $origins = Origin::all();

        return response()->json(['success' => true, 'data' => $origins, 'error' => null]);
    }
}
