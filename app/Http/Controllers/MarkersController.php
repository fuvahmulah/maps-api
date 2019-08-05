<?php

namespace App\Http\Controllers;

use App\Http\Requests\Marker\CreateMarker;
use App\Marker;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\Geometry;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Arr;

class MarkersController extends Controller
{
    /**
     * Creates a marker
     *
     * @param  CreateMarker  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateMarker $request)
    {
        $attributes = Arr::except($request->validated(), ['lat', 'long']);
        $attributes['created_by'] = auth()->id();
        $attributes['geometry'] = new Point($request->get('lat'), $request->get('long'));
        $marker = Marker::create($attributes);

        return response()->json($marker->toFeature(), 201);
    }
    /**
     * Returns all markers as a geojson feed
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoJson(Request $request)
    {
        $markers = Marker::all();

        $features = [];

        foreach ($markers as $marker) {
            $data = $marker->geometry->toJson();

            $features[] = $marker->toFeature();
        }

        $collection = new FeatureCollection($features);

        return response()->json($collection)->setCallback('geo_callback');
    }

    /**
     * Returns the closes neighbors to a given cordinate
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighbors(Request $request)
    {
        //        $lat = 73.4255247;
        //        $lon = -0.2971667;

        $lat = $request->get('lat');
        $lon = $request->get('long');

        if (empty($lat) || empty($lon)) {
            return response()->json(['message' => 'Query paramters :lat and :lon is required.'], 403);
        }

        // todo: this filter option for marker types need to be set

        $markers = Marker::whereRaw("MBRCONTAINS(
        LineString(
            POINT(? + .2 / 111.1, ? + .4 / (111.1 / COS(RADIANS(?)))),
            POINT(? - .2 / 111.1, ? - .4 / (111.1 / COS(RADIANS(?))))),
            geometry
        )", [$lon, $lat, $lon, $lon, $lat, $lon])
            ->orderByRaw("GLength(LineString(geometry, POINT(?, ?))) asc", [$lon, $lat])
            ->take(20)
            ->get();

        $features = [];

        foreach ($markers as $marker) {
            $features[] = $marker->toFeature();
        }

        $collection = new FeatureCollection($features);

        return response()->json($collection);
    }

    public function locations(Request $request)
    {
        $keyword = $request->get('keyword');
        $markerCollections = Marker::where('name', 'like', $keyword . '%')->limit(10)->get()->map(function ($marker) {
           return $marker->toFeature();
        });
        return response()->json($markerCollections);
    }
}
