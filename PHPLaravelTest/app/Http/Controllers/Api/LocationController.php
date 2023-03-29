<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LocationController extends Controller
{
    /**
     * Return all locations
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('lat') && $request->has('lng')) {
            $radius = $request->has('radius') ? $request->get('radius') : null;

            $locations = Location::filterByMilesRadius((float)$request->get('lat'), (float)$request->get('lng'), (int)$radius)->get();
        } else {
            $locations = Location::all();
        }

        $data = new LocationCollection($locations);

        return Response::json(['data' => $data])->setStatusCode(200);
    }

    /**
     * Return a single location
     */
    public function show(Request $request, string $siteNumber)
    {
        if (!$location = Location::where('site_number', 'LIKE', '%'.$siteNumber.'%')->first()) {
            return Response::make('Not Found')->setStatusCode(404);
        }

        $data = new LocationResource($location);

        return Response::json([ 'data' => $data ])->setStatusCode(200);
    }
}
